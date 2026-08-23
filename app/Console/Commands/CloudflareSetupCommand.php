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
            $backendUrl = $this->ask('What is your Laravel app backend URL?', $backendUrl ?: 'http://localhost:8000');
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
            $this->error("Cloudflare worker directory not found at {$workerDir}");

            return self::FAILURE;
        }

        $devVarsContent = "BACKEND_URL={$backendUrl}\nWORKER_SECRET={$secret}\n";

        if (file_put_contents($devVarsPath, $devVarsContent) !== false) {
            $this->info("Successfully generated local development vars file at {$devVarsPath}");
        } else {
            $this->error("Failed to write to {$devVarsPath}");

            return self::FAILURE;
        }

        // 4. Handle Deployment if --deploy option is set
        if ($this->option('deploy')) {
            $this->info('Deploying secrets to Cloudflare...');

            // Use local wrangler from node_modules to avoid npx PATH issues
            $isWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
            $wranglerBin = $isWindows
                ? 'node_modules\\.bin\\wrangler.cmd'
                : 'node_modules/.bin/wrangler';

            // Install node_modules if missing
            if (! file_exists($workerDir.DIRECTORY_SEPARATOR.$wranglerBin)) {
                $this->comment('Installing worker dependencies (npm install)...');
                $npmCmd = $isWindows ? 'cmd /c npm install' : 'npm install';
                $npmResult = Process::path($workerDir)->timeout(120)->run($npmCmd);
                if (! $npmResult->successful()) {
                    $this->error('Failed to install worker dependencies.');
                    $this->error($npmResult->errorOutput() ?: $npmResult->output());

                    return self::FAILURE;
                }
            }

            $wrangler = $isWindows ? "cmd /c {$wranglerBin}" : $wranglerBin;

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

            // Merge Cloudflare credentials with current system env so PATH/node remain available
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
