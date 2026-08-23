<?php

namespace App\Console\Commands;

use App\Models\Domain;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Redis;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\password;
use function Laravel\Prompts\text;

class InstallCommand extends Command
{
    protected $signature = 'app:install';

    protected $description = 'Interactive installation wizard for Cloudflare Temp Mail';

    public function handle(): int
    {
        $this->components->info('Cloudflare Temp Mail — Installation Wizard');
        $this->newLine();

        $this->line('Panduan instalasi:');
        $this->line('  1. Siapkan PHP 8.2+, Composer, Node.js/NPM, dan database MySQL/PostgreSQL/SQLite.');
        $this->line('  2. Siapkan Redis untuk queue, caching, dan rate limiter.');
        $this->line('  3. Siapkan domain email di Cloudflare Email Routing.');
        $this->line('  4. Ikuti pertanyaan wizard: environment, database, migration, admin, domain, dan frontend.');
        $this->line('  5. Setelah selesai, jalankan server, queue worker, dan scheduler.');
        $this->line('  6. Deploy Cloudflare Worker lalu arahkan Catch-All Email Routing ke worker.');
        $this->newLine();

        // 0. Check & fix permissions
        $this->checkAndFixPermissions();

        // 1. Environment file
        if (! file_exists(base_path('.env'))) {
            copy(base_path('.env.example'), base_path('.env'));
            $this->components->info('.env file created from .env.example');
        } else {
            $this->components->info('.env file already exists, skipping.');
        }

        // 2. Application key
        if (empty(config('app.key')) || config('app.key') === 'base64:') {
            $this->call('key:generate', ['--no-interaction' => true]);
        } else {
            $this->components->info('Application key already set, skipping.');
        }

        // 3. Database configuration
        if (confirm('Configure database connection?', default: true)) {
            $this->configureDatabaseEnv();
        }

        $this->checkRedis();
        $this->checkCloudflareConfiguration();

        // 4. Run migrations
        if (confirm('Run database migrations?', default: true)) {
            $this->call('migrate', ['--no-interaction' => true]);
            $this->components->info('Migrations completed.');
        }

        // 5. Create admin user
        if (confirm('Create admin user?', default: ! User::where('is_admin', true)->exists())) {
            $this->createAdminUser();
        }

        // 6. Add first domain
        if (confirm('Add email domain?', default: ! Domain::exists())) {
            $this->addDomain();
        }

        // 7. Frontend build
        if (confirm('Install & build frontend assets (npm install && npm run build)?', default: true)) {
            $this->buildFrontend();
        }

        // 8. Summary
        $this->newLine();
        $this->components->info('✅ Installation complete!');
        $this->newLine();

        $this->table(['Setting', 'Value'], [
            ['App URL', config('app.url')],
            ['Database', config('database.default')],
            ['Admin Users', User::where('is_admin', true)->count()],
            ['Domains', Domain::where('is_active', true)->count()],
        ]);

        $this->newLine();
        $this->components->info('Next steps:');
        $this->line('  1. php artisan serve');
        $this->line('  2. php artisan queue:work');
        if (PHP_OS_FAMILY === 'Windows') {
            $this->line('  3. Windows: php artisan schedule:work (atau Task Scheduler)');
        } else {
            $this->line('  3. Linux/macOS: * * * * * cd '.base_path().' && php artisan schedule:run >> /dev/null 2>&1');
        }
        $this->line('  4. php artisan cloudflare:setup --deploy (untuk menerima email)');
        $this->line('  5. Atur Catch-All Cloudflare Email Routing ke worker email-worker');
        $this->line('  6. Pastikan CLOUDFLARE_API_TOKEN dan CLOUDFLARE_ACCOUNT_ID tersedia sebelum deploy');
        $this->line('  7. Lihat cloudflare/README.md untuk panduan Cloudflare lengkap');

        return self::SUCCESS;
    }

    private function checkAndFixPermissions(): void
    {
        $directories = [
            storage_path(),
            storage_path('app'),
            storage_path('app/public'),
            storage_path('framework'),
            storage_path('framework/cache'),
            storage_path('framework/cache/data'),
            storage_path('framework/sessions'),
            storage_path('framework/views'),
            storage_path('logs'),
            base_path('bootstrap/cache'),
            base_path('cloudflare/email-worker'),
        ];

        $failed = [];

        foreach ($directories as $dir) {
            if (! is_dir($dir)) {
                @mkdir($dir, 0775, true);
            }

            if (! is_writable($dir)) {
                @chmod($dir, 0775);
            }

            if (! is_writable($dir)) {
                $failed[] = $dir;
            }
        }

        // Test temp directory
        $sysTemp = sys_get_temp_dir();
        $tempFile = @tempnam($sysTemp, 'mailtemp_');
        if ($tempFile && file_exists($tempFile)) {
            @unlink($tempFile);
        } else {
            $failed[] = "System temp directory ({$sysTemp}) is not writable or restricted by open_basedir";
        }

        if (empty($failed)) {
            $this->components->info('Storage and temporary directory permissions verified (Read/Write OK).');
        } else {
            $this->components->warn('Permission warnings detected:');
            foreach ($failed as $item) {
                $this->line("  - {$item}");
            }
            $this->line('  Tip for Linux/aaPanel: sudo chown -R www-data:www-data storage bootstrap/cache && sudo chmod -R 775 storage bootstrap/cache');
        }
    }

    private function configureDatabaseEnv(): void
    {
        $connection = text('Database connection', default: 'mysql', hint: 'mysql, pgsql, sqlite');
        $envPath = base_path('.env');
        $env = file_get_contents($envPath);

        if ($connection === 'sqlite') {
            $env = preg_replace('/^DB_CONNECTION=.*$/m', 'DB_CONNECTION=sqlite', $env);
            // Comment out other DB_ lines
            foreach (['DB_HOST', 'DB_PORT', 'DB_DATABASE', 'DB_USERNAME', 'DB_PASSWORD'] as $key) {
                $env = preg_replace('/^('.$key.'=.*)$/m', '# $1', $env);
            }
            file_put_contents($envPath, $env);
            config()->set('database.default', 'sqlite');
            DB::purge('sqlite');
            $this->components->info('Database set to SQLite.');

            return;
        }

        $host = text('Database host', default: '127.0.0.1');
        $port = text('Database port', default: $connection === 'pgsql' ? '5432' : '3306');
        $database = text('Database name', default: 'cloudflare_temp_mail');
        $username = text('Database username', default: 'root');
        $dbPassword = password('Database password');

        $replacements = [
            'DB_CONNECTION' => $connection,
            'DB_HOST' => $host,
            'DB_PORT' => $port,
            'DB_DATABASE' => $database,
            'DB_USERNAME' => $username,
            'DB_PASSWORD' => $dbPassword,
        ];

        foreach ($replacements as $key => $value) {
            // Uncomment if commented
            $env = preg_replace('/^#\s*('.$key.'=.*)$/m', '$1', $env);
            // Replace value
            $env = preg_replace('/^'.$key.'=.*$/m', "{$key}={$value}", $env);
        }

        file_put_contents($envPath, $env);
        config()->set('database.default', $connection);
        config()->set("database.connections.{$connection}.host", $host);
        config()->set("database.connections.{$connection}.port", $port);
        config()->set("database.connections.{$connection}.database", $database);
        config()->set("database.connections.{$connection}.username", $username);
        config()->set("database.connections.{$connection}.password", $dbPassword);
        DB::purge($connection);
        $this->components->info('Database configuration saved to .env');
    }

    private function checkRedis(): void
    {
        try {
            Redis::connection()->ping();
            $this->components->info('Redis connection verified.');
        } catch (\Throwable $exception) {
            $this->components->warn('Redis unavailable. Queue, cache, and rate limiter need Redis before production use.');
        }
    }

    private function checkCloudflareConfiguration(): void
    {
        $missing = collect([
            'CLOUDFLARE_API_TOKEN' => env('CLOUDFLARE_API_TOKEN'),
            'CLOUDFLARE_ACCOUNT_ID' => env('CLOUDFLARE_ACCOUNT_ID'),
        ])->filter(fn (?string $value): bool => empty($value))->keys();

        if ($missing->isEmpty()) {
            $this->components->info('Cloudflare credentials found.');

            return;
        }

        $this->components->warn('Cloudflare credentials missing: '.$missing->implode(', ').'. Configure them in Admin Settings or Setup Wizard before deployment.');
    }

    private function createAdminUser(): void
    {
        $name = text('Admin name', default: 'Admin');
        $email = text('Admin email', default: 'admin@emailtemp.com');
        $adminPassword = password('Admin password', hint: 'min 8 characters');

        if (empty($adminPassword) || strlen($adminPassword) < 8) {
            $this->components->error('Password must be at least 8 characters.');

            return;
        }

        User::updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => $adminPassword,
                'is_admin' => true,
            ]
        );

        $this->components->info("Admin user created: {$email}");
    }

    private function addDomain(): void
    {
        $domain = text(
            label: 'Domain name',
            placeholder: 'example.com',
            default: '',
            validate: function (string $value): ?string {
                $trimmed = trim($value);
                if ($trimmed === '') {
                    return null;
                }

                return filter_var($trimmed, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME)
                    ? null
                    : 'Enter valid domain, for example example.com.';
            }
        );

        $domain = trim($domain);

        if (empty($domain)) {
            $this->components->warn('No domain entered, skipping.');

            return;
        }

        Domain::firstOrCreate(
            ['domain' => $domain],
            ['is_active' => true]
        );

        $this->components->info("Domain added: {$domain}");
    }

    private function buildFrontend(): void
    {
        $isWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
        $npm = $isWindows ? 'cmd /c npm' : 'npm';

        $this->components->info('Running npm install...');
        $install = Process::path(base_path())->run("{$npm} install");
        if (! $install->successful()) {
            $this->components->error('npm install failed: '.$install->errorOutput());

            return;
        }

        $this->components->info('Running npm run build...');
        $build = Process::path(base_path())->run("{$npm} run build");
        if (! $build->successful()) {
            $this->components->error('npm run build failed: '.$build->errorOutput());

            return;
        }

        $this->components->info('Frontend assets built successfully.');
    }
}
