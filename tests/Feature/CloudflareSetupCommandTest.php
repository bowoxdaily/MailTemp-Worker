<?php

use App\Models\Setting;
use Illuminate\Support\Facades\Process;

use function Pest\Laravel\artisan;

$getDevVarsPath = function () {
    return base_path('cloudflare/email-worker/.dev.vars');
};

beforeEach(function () use ($getDevVarsPath) {
    $path = $getDevVarsPath();
    if (file_exists($path)) {
        unlink($path);
    }
});

afterEach(function () use ($getDevVarsPath) {
    $path = $getDevVarsPath();
    if (file_exists($path)) {
        unlink($path);
    }
});

test('it generates .dev.vars file successfully', function () use ($getDevVarsPath) {
    // Mock the backend URL configuration
    config(['app.url' => 'https://example.com']);

    // Set setting in database if possible, or we let it fall back
    Setting::updateOrCreate(['key' => 'cloudflare_worker_secret'], ['value' => 'my-test-secret']);

    $path = $getDevVarsPath();

    artisan('cloudflare:setup')
        ->expectsOutput('Starting Cloudflare Worker setup...')
        ->expectsOutput("Successfully generated local development vars file at {$path}")
        ->assertExitCode(0);

    expect(file_exists($path))->toBeTrue();

    $content = file_get_contents($path);
    expect($content)->toContain('BACKEND_URL=https://example.com');
    expect($content)->toContain('WORKER_SECRET=my-test-secret');
});

test('it handles fallback secret generation if setting is not in db', function () use ($getDevVarsPath) {
    config(['app.url' => 'https://example.com']);

    // Delete setting from db
    Setting::where('key', 'cloudflare_worker_secret')->delete();

    $path = $getDevVarsPath();

    artisan('cloudflare:setup')
        ->expectsOutput('Starting Cloudflare Worker setup...')
        ->expectsOutput("Successfully generated local development vars file at {$path}")
        ->assertExitCode(0);

    expect(file_exists($path))->toBeTrue();

    $content = file_get_contents($path);
    expect($content)->toContain('BACKEND_URL=https://example.com');
    // It should have generated a random 64-char string secret
    expect($content)->toMatch('/WORKER_SECRET=[a-zA-Z0-9]{64}/');
});

test('it prompts for URL if app.url is default or empty', function () use ($getDevVarsPath) {
    config(['app.url' => 'http://localhost']);
    Setting::updateOrCreate(['key' => 'cloudflare_worker_secret'], ['value' => 'some-secret']);

    $path = $getDevVarsPath();

    artisan('cloudflare:setup')
        ->expectsQuestion('What is your Laravel app backend URL?', 'https://prompted-url.com')
        ->expectsOutput("Successfully generated local development vars file at {$path}")
        ->assertExitCode(0);

    expect(file_exists($path))->toBeTrue();
    $content = file_get_contents($path);
    expect($content)->toContain('BACKEND_URL=https://prompted-url.com');
});

test('it deploys secrets and worker when --deploy is provided', function () {
    config(['app.url' => 'https://example.com']);
    Setting::updateOrCreate(['key' => 'cloudflare_worker_secret'], ['value' => 'deploy-secret']);
    Setting::updateOrCreate(['key' => 'cloudflare_api_token'], ['value' => 'test-api-token']);
    Setting::updateOrCreate(['key' => 'cloudflare_account_id'], ['value' => 'test-account-id']);

    Process::fake([
        '*npm install*' => Process::result('', '', 0),
        '*wrangler* secret put BACKEND_URL*' => Process::result('', '', 0),
        '*wrangler* secret put WORKER_SECRET*' => Process::result('', '', 0),
        '*wrangler* deploy*' => Process::result('Deployed to cloudflare successfully', '', 0),
    ]);

    artisan('cloudflare:setup --deploy')
        ->expectsOutput('Starting Cloudflare Worker setup...')
        ->expectsOutput('Deploying secrets to Cloudflare...')
        ->expectsOutput('BACKEND_URL secret set successfully.')
        ->expectsOutput('WORKER_SECRET secret set successfully.')
        ->expectsOutput('Deploying Cloudflare Worker...')
        ->expectsOutput('Cloudflare Worker deployed successfully!')
        ->assertExitCode(0);

    // Verify Process calls (use 'secret put' to match both wrangler and wrangler.cmd)
    Process::assertRan(function ($process) {
        return str_contains($process->command, 'secret put BACKEND_URL')
            && $process->input === 'https://example.com'
            && ($process->environment['CLOUDFLARE_API_TOKEN'] ?? null) === 'test-api-token'
            && ($process->environment['CLOUDFLARE_ACCOUNT_ID'] ?? null) === 'test-account-id';
    });

    Process::assertRan(function ($process) {
        return str_contains($process->command, 'secret put WORKER_SECRET')
            && $process->input === 'deploy-secret'
            && ($process->environment['CLOUDFLARE_API_TOKEN'] ?? null) === 'test-api-token'
            && ($process->environment['CLOUDFLARE_ACCOUNT_ID'] ?? null) === 'test-account-id';
    });

    Process::assertRan(function ($process) {
        return str_contains($process->command, 'wrangler') && str_contains($process->command, 'deploy')
            && ($process->environment['CLOUDFLARE_API_TOKEN'] ?? null) === 'test-api-token'
            && ($process->environment['CLOUDFLARE_ACCOUNT_ID'] ?? null) === 'test-account-id';
    });
});
