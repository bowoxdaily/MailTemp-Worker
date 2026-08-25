<?php

namespace App\Console\Commands;

use App\Models\Setting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Str;

class CloudflareSetupCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cloudflare:setup
                            {--deploy : Deploy the Cloudflare Worker and set production secrets}
                            {--secret= : Override the Cloudflare Worker secret}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set up Cloudflare Worker configuration, generate local vars, and deploy secrets';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Starting Cloudflare Worker setup...');

        // 1. Determine Backend URL
        $backendUrl = config('app.url');
        if (empty($backendUrl) || $backendUrl === 'http://localhost') {
            if ($this->input->isInteractive()) {
                $backendUrl = $this->ask('What is your Laravel app backend URL?', $backendUrl ?: 'http://localhost:8000');
            } else {
                $backendUrl = $backendUrl ?: 'http://localhost:8000';
            }
        }
        $backendUrl = rtrim(trim($backendUrl), '/');

        // 2. Determine Worker Secret
        $secret = $this->option('secret');
        if (empty($secret)) {
            try {
                $secret = Setting::get('cloudflare_worker_secret');
            } catch (\Exception $e) {
                // Database might not be migrated or connected yet
            }
        }

        if (empty($secret)) {
            $secret = Str::random(64);
            $this->info("Generated new worker secret: {$secret}");

            // Attempt to save to database if setting exists
            try {
                $setting = Setting::where('key', 'cloudflare_worker_secret')->first();
                if ($setting) {
                    $setting->update(['value' => $secret]);
                    $this->info('Saved worker secret to settings table.');
                }
            } catch (\Exception $e) {
                $this->warn('Could not save secret to settings table (database might not be migrated).');
            }
        }

        // 3. Create or Update .dev.vars for local wrangler dev
        $workerDir = base_path('cloudflare/email-worker');
        $devVarsPath = $workerDir.'/.dev.vars';

        if (! is_dir($workerDir)) {
            @mkdir($workerDir, 0775, true);
        }

        if (! is_dir($workerDir)) {
            $this->error("Cloudflare worker directory not found at {$workerDir}");

            return self::FAILURE;
        }

        // Try fixing permissions if not writable
        if (! is_writable($workerDir)) {
            @chmod($workerDir, 0777);
        }
        if (file_exists($devVarsPath) && ! is_writable($devVarsPath)) {
            @chmod($devVarsPath, 0666);
        }
        $cacheDir = $workerDir.'/node_modules/.cache';
        if (is_dir($cacheDir)) {
            @chmod($cacheDir, 0777);
            @chmod($cacheDir.'/wrangler', 0777);
        }

        $devVarsContent = "BACKEND_URL={$backendUrl}\nWORKER_SECRET={$secret}\n";

        $writeResult = @file_put_contents($devVarsPath, $devVarsContent);
        if ($writeResult !== false) {
            $this->info("Successfully generated local development vars file at {$devVarsPath}");
        } else {
            $this->warn("Could not write to {$devVarsPath} (permission denied). Continuing deployment...");
        }

        // 4. Handle Deployment if --deploy option is set
        if ($this->option('deploy')) {
            if (! function_exists('proc_open')) {
                $this->error('PHP function "proc_open" is disabled or unavailable on this server.');
                $this->line('aaPanel/cPanel solution:');
                $this->line('1. Open aaPanel -> App Store -> PHP-8.x Settings -> Disabled functions.');
                $this->line('2. Find and delete "proc_open" (and "putenv" / "exec" if needed) from disabled functions.');
                $this->line('3. Restart PHP service, then retry deploying.');

                return self::FAILURE;
            }

            $this->info('Deploying secrets to Cloudflare...');

            $isWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';

            // Find modern Node.js and npm paths (Windows / aaPanel / nvm / standard paths)
            $nodePaths = $isWindows ? [
                'C:\Program Files\nodejs',
                'C:\Program Files (x86)\nodejs',
                (getenv('ProgramFiles') ?: 'C:\Program Files').'\nodejs',
                (getenv('ProgramFiles(x86)') ?: 'C:\Program Files (x86)').'\nodejs',
                (getenv('APPDATA') ?: '').'\npm',
                (getenv('LOCALAPPDATA') ?: '').'\Programs\node',
            ] : [
                '/www/server/nodejs/v22.*/bin',
                '/www/server/nodejs/v20.*/bin',
                '/www/server/nodejs/v18.*/bin',
                '/www/server/nvm/versions/node/v22.*/bin',
                '/www/server/nvm/versions/node/v20.*/bin',
                '/www/server/nvm/versions/node/v18.*/bin',
                '/root/.nvm/versions/node/v22.*/bin',
                '/root/.nvm/versions/node/v20.*/bin',
                '/root/.nvm/versions/node/v18.*/bin',
                '/home/*/.nvm/versions/node/v22.*/bin',
                '/home/*/.nvm/versions/node/v20.*/bin',
                '/home/*/.nvm/versions/node/v18.*/bin',
                '/usr/local/bin',
                '/usr/bin',
            ];

            $extraPaths = [];
            $preferredNodeBin = null;
            $npmExecutable = null;

            foreach ($nodePaths as $pattern) {
                if (empty($pattern)) {
                    continue;
                }
                $matches = str_contains($pattern, '*') ? glob($pattern) : (is_dir($pattern) ? [$pattern] : []);
                if ($matches) {
                    if (str_contains($pattern, '*')) {
                        rsort($matches);
                    }
                    foreach ($matches as $match) {
                        $candidateNode = $match.DIRECTORY_SEPARATOR.($isWindows ? 'node.exe' : 'node');
                        $candidateNpm = $match.DIRECTORY_SEPARATOR.($isWindows ? 'npm.cmd' : 'npm');
                        if (is_dir($match)) {
                            $extraPaths[] = $match;
                        }
                        if (! $preferredNodeBin && file_exists($candidateNode)) {
                            $preferredNodeBin = $candidateNode;
                        }
                        if (! $npmExecutable && file_exists($candidateNpm)) {
                            $npmExecutable = $candidateNpm;
                        }
                    }
                }
            }

            // Get Cloudflare credentials from Settings or environment
            $apiToken = null;
            $accountId = null;
            try {
                $apiToken = Setting::get('cloudflare_api_token') ?: env('CLOUDFLARE_API_TOKEN');
                $accountId = Setting::get('cloudflare_account_id') ?: env('CLOUDFLARE_ACCOUNT_ID');
            } catch (\Exception $e) {
                $apiToken = env('CLOUDFLARE_API_TOKEN');
                $accountId = env('CLOUDFLARE_ACCOUNT_ID');
            }

            // Merge Cloudflare credentials and extended PATH
            $env = getenv();
            if (is_array($env) === false) {
                $env = [];
            }
            if (! empty($apiToken)) {
                $env['CLOUDFLARE_API_TOKEN'] = $apiToken;
            }
            if (! empty($accountId)) {
                $env['CLOUDFLARE_ACCOUNT_ID'] = $accountId;
            }
            $currentPath = $env['PATH'] ?? (getenv('PATH') ?: '');
            if (! empty($extraPaths)) {
                $env['PATH'] = implode(PATH_SEPARATOR, array_unique(array_merge($extraPaths, explode(PATH_SEPARATOR, $currentPath))));
            }

            // Use local wrangler from node_modules to avoid npx PATH issues
            $wranglerBin = $isWindows
                ? 'node_modules\\.bin\\wrangler.cmd'
                : 'node_modules/.bin/wrangler';

            // Install node_modules if missing
            if (! file_exists($workerDir.DIRECTORY_SEPARATOR.$wranglerBin)) {
                $this->comment('Installing worker dependencies (npm install)...');
                if ($npmExecutable && $isWindows) {
                    $npmCmd = "cmd /c \"\"{$npmExecutable}\" install\"";
                } elseif ($isWindows) {
                    $npmCmd = 'cmd /c npm install';
                } else {
                    $npmCmd = $npmExecutable ? "{$npmExecutable} install" : 'npm install';
                }
                $npmResult = Process::path($workerDir)->env($env)->timeout(120)->run($npmCmd);
                if (! $npmResult->successful()) {
                    $this->error('Failed to install worker dependencies.');
                    $this->error($npmResult->errorOutput() ?: $npmResult->output());

                    return self::FAILURE;
                }
            }

            $wrangler = $isWindows ? "cmd /c {$wranglerBin}" : $wranglerBin;

            // If we have a verified node >= 16 binary, invoke wrangler entry script with it directly to bypass old system node
            $wranglerJs = $workerDir.'/node_modules/wrangler/bin/wrangler.js';
            if ($preferredNodeBin && file_exists($wranglerJs) && ! $isWindows) {
                $wrangler = "{$preferredNodeBin} {$wranglerJs}";
            }

            // Deploy BACKEND_URL secret
            $this->comment('Setting BACKEND_URL secret...');
            $resultUrl = Process::path($workerDir)
                ->env($env)
                ->timeout(120)
                ->input($backendUrl)
                ->run("{$wrangler} secret put BACKEND_URL");

            if (! $resultUrl->successful()) {
                $this->error('Failed to set BACKEND_URL secret on Cloudflare.');
                $this->error($resultUrl->errorOutput() ?: $resultUrl->output());
                $this->warn('Make sure you are logged in to Wrangler (run "npx wrangler login") or Cloudflare API Token is configured.');

                return self::FAILURE;
            }
            $this->info('BACKEND_URL secret set successfully.');

            // Deploy WORKER_SECRET secret
            $this->comment('Setting WORKER_SECRET secret...');
            $resultSecret = Process::path($workerDir)
                ->env($env)
                ->timeout(120)
                ->input($secret)
                ->run("{$wrangler} secret put WORKER_SECRET");

            if (! $resultSecret->successful()) {
                $this->error('Failed to set WORKER_SECRET secret on Cloudflare.');
                $this->error($resultSecret->errorOutput() ?: $resultSecret->output());

                return self::FAILURE;
            }
            $this->info('WORKER_SECRET secret set successfully.');

            // Deploy Worker
            $this->info('Deploying Cloudflare Worker...');
            $resultDeploy = Process::path($workerDir)
                ->env($env)
                ->timeout(120)
                ->run("{$wrangler} deploy");

            if (! $resultDeploy->successful()) {
                $this->error('Failed to deploy Cloudflare Worker.');
                $this->error($resultDeploy->errorOutput() ?: $resultDeploy->output());

                return self::FAILURE;
            }
            $this->info('Cloudflare Worker deployed successfully!');
        }

        $this->info('Cloudflare Worker setup completed successfully.');

        return self::SUCCESS;
    }
}
