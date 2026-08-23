<?php

use App\Models\Domain;
use App\Models\User;
use Illuminate\Support\Facades\Process;

use function Pest\Laravel\artisan;

test('install command runs full wizard skipping all optional steps', function () {
    artisan('app:install')
        ->expectsConfirmation('Configure database connection?', 'no')
        ->expectsConfirmation('Run database migrations?', 'no')
        ->expectsConfirmation('Create admin user?', 'no')
        ->expectsConfirmation('Add email domain?', 'no')
        ->expectsConfirmation('Install & build frontend assets (npm install && npm run build)?', 'no')
        ->assertExitCode(0);
});

test('install command creates admin user when confirmed', function () {
    artisan('app:install')
        ->expectsConfirmation('Configure database connection?', 'no')
        ->expectsConfirmation('Run database migrations?', 'no')
        ->expectsConfirmation('Create admin user?', 'yes')
        ->expectsQuestion('Admin name', 'Test Admin')
        ->expectsQuestion('Admin email', 'testadmin@example.com')
        ->expectsQuestion('Admin password', 'secretpwd')
        ->expectsConfirmation('Add email domain?', 'no')
        ->expectsConfirmation('Install & build frontend assets (npm install && npm run build)?', 'no')
        ->assertExitCode(0);

    $admin = User::where('email', 'testadmin@example.com')->first();
    expect($admin)->not->toBeNull()
        ->and($admin->is_admin)->toBeTrue()
        ->and($admin->name)->toBe('Test Admin');
});

test('install command adds domain when confirmed', function () {
    artisan('app:install')
        ->expectsConfirmation('Configure database connection?', 'no')
        ->expectsConfirmation('Run database migrations?', 'no')
        ->expectsConfirmation('Create admin user?', 'no')
        ->expectsConfirmation('Add email domain?', 'yes')
        ->expectsQuestion('Domain name', 'testdomain.com')
        ->expectsConfirmation('Install & build frontend assets (npm install && npm run build)?', 'no')
        ->assertExitCode(0);

    expect(Domain::where('domain', 'testdomain.com')->exists())->toBeTrue();
});

test('install command runs migrations when confirmed', function () {
    artisan('app:install')
        ->expectsConfirmation('Configure database connection?', 'no')
        ->expectsConfirmation('Run database migrations?', 'yes')
        ->expectsConfirmation('Create admin user?', 'no')
        ->expectsConfirmation('Add email domain?', 'no')
        ->expectsConfirmation('Install & build frontend assets (npm install && npm run build)?', 'no')
        ->assertExitCode(0);
});

test('install command builds frontend when confirmed', function () {
    Process::fake([
        '*npm install*' => Process::result('', '', 0),
        '*npm run build*' => Process::result('', '', 0),
    ]);

    artisan('app:install')
        ->expectsConfirmation('Configure database connection?', 'no')
        ->expectsConfirmation('Run database migrations?', 'no')
        ->expectsConfirmation('Create admin user?', 'no')
        ->expectsConfirmation('Add email domain?', 'no')
        ->expectsConfirmation('Install & build frontend assets (npm install && npm run build)?', 'yes')
        ->assertExitCode(0);

    Process::assertRan(fn ($p) => str_contains($p->command, 'npm install'));
    Process::assertRan(fn ($p) => str_contains($p->command, 'npm run build'));
});

test('install command rejects short admin password', function () {
    artisan('app:install')
        ->expectsConfirmation('Configure database connection?', 'no')
        ->expectsConfirmation('Run database migrations?', 'no')
        ->expectsConfirmation('Create admin user?', 'yes')
        ->expectsQuestion('Admin name', 'Admin')
        ->expectsQuestion('Admin email', 'short@test.com')
        ->expectsQuestion('Admin password', 'short')
        ->expectsConfirmation('Add email domain?', 'no')
        ->expectsConfirmation('Install & build frontend assets (npm install && npm run build)?', 'no')
        ->assertExitCode(0);

    expect(User::where('email', 'short@test.com')->exists())->toBeFalse();
});
