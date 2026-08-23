<?php

use App\Http\Controllers\SetupController;
use App\Models\Domain;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

Route::get('/setup', [SetupController::class, 'index'])->name('setup.index');
Route::post('/setup', [SetupController::class, 'store'])->name('setup.store');
Route::get('/setup/cloudflare', [SetupController::class, 'cloudflare'])->name('setup.cloudflare');
Route::post('/setup/deploy-worker', [SetupController::class, 'deployWorker'])->name('setup.deploy-worker');
Route::get('/setup/complete', [SetupController::class, 'complete'])->name('setup.complete');

Route::get('/', function () {
    if (! User::where('is_admin', true)->exists()) {
        return redirect()->route('setup.index');
    }

    $domains = Domain::where('is_active', true)->pluck('domain')->toArray();

    return view('home', compact('domains'));
})->name('home');

$seoPages = [
    'temp-mail' => [
        'name' => 'Temp Mail',
        'title' => 'Temp Mail Gratis — Email Sementara Tanpa Registrasi',
        'description' => 'Gunakan temp mail gratis untuk OTP, verifikasi, dan testing. Buat temporary email tanpa akun, baca inbox, lalu hapus otomatis.',
        'intro' => 'Temp mail gratis untuk kebutuhan cepat',
        'copy' => 'Buat alamat email sementara tanpa registrasi. Terima email verifikasi, baca OTP, dan biarkan inbox terhapus otomatis saat masa aktif berakhir.',
    ],
    'temporary-email' => [
        'name' => 'Temporary Email',
        'title' => 'Temporary Email Gratis — Buat Email Sementara Instan',
        'description' => 'Temporary email gratis tanpa login untuk verifikasi akun, OTP, dan testing email. Inbox sementara terhapus otomatis setelah kedaluwarsa.',
        'intro' => 'Temporary email instan tanpa login',
        'copy' => 'Lindungi alamat email utama saat mendaftar layanan atau menguji alur email. EmailTemp memberi inbox sementara yang siap dipakai dalam hitungan detik.',
    ],
    '10-minute-mail' => [
        'name' => '10 Minute Mail',
        'title' => '10 Minute Mail Gratis — Email Sekali Pakai untuk OTP',
        'description' => '10 minute mail gratis untuk menerima OTP dan email verifikasi. Tanpa akun, tanpa spam ke inbox utama, dan data terhapus otomatis.',
        'intro' => '10 minute mail untuk verifikasi cepat',
        'copy' => 'Pilih masa aktif 10 menit sebagai default, salin alamatnya, lalu tunggu email masuk. Cocok untuk kode OTP dan verifikasi sekali pakai.',
    ],
    'disposable-email' => [
        'name' => 'Disposable Email',
        'title' => 'Disposable Email Gratis — Inbox Sekali Pakai',
        'description' => 'Disposable email gratis untuk menjaga inbox utama tetap bersih. Tidak perlu registrasi; alamat dan pesan hilang setelah masa aktif selesai.',
        'intro' => 'Disposable email untuk privasi sehari-hari',
        'copy' => 'Gunakan alamat disposable email ketika tidak ingin membagikan email utama. Tidak ada password yang perlu diingat dan tidak ada inbox permanen.',
    ],
    'temporary-email-generator' => [
        'name' => 'Temporary Email Generator',
        'title' => 'Temporary Email Generator — Buat Email Sementara Gratis',
        'description' => 'Temporary email generator gratis untuk developer, QA, dan pengguna umum. Generate alamat email instan, pantau inbox, dan hapus data otomatis.',
        'intro' => 'Temporary email generator untuk testing',
        'copy' => 'Generate alamat email sementara untuk menguji signup, notifikasi, dan verifikasi aplikasi. Inbox mendukung email HTML, plain text, dan attachment yang diterima.',
    ],
];

foreach ($seoPages as $slug => $page) {
    Route::get("/{$slug}", function () use ($page, $seoPages) {
        return view('seo.landing', compact('page', 'seoPages'));
    })->name("seo.{$slug}");
}

Route::get('/sitemap.xml', function () use ($seoPages) {
    $legalRoutes = ['legal.terms', 'legal.privacy', 'legal.cookies', 'legal.contact'];

    $mainUrls = collect(['home', ...array_map(fn(string $slug): string => "seo.{$slug}", array_keys($seoPages))])
        ->map(fn(string $route): string => '<url><loc>' . e(route($route)) . '</loc><changefreq>weekly</changefreq><priority>' . ($route === 'home' ? '1.0' : '0.8') . '</priority></url>')
        ->implode('');

    $legalUrls = collect($legalRoutes)
        ->map(fn(string $route): string => '<url><loc>' . e(route($route)) . '</loc><changefreq>monthly</changefreq><priority>0.5</priority></url>')
        ->implode('');

    return response("<?xml version=\"1.0\" encoding=\"UTF-8\"?><urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">{$mainUrls}{$legalUrls}</urlset>", 200, [
        'Content-Type' => 'application/xml; charset=UTF-8',
    ]);
})->name('sitemap');

Route::get('/robots.txt', function (): Response {
    return response("User-agent: *\nAllow: /\nDisallow: /admin\nDisallow: /api\nSitemap: " . url('/sitemap.xml') . "\n", 200, [
        'Content-Type' => 'text/plain; charset=UTF-8',
    ]);
})->name('robots');

Route::view('/syarat-ketentuan', 'legal.terms')->name('legal.terms');
Route::view('/kebijakan-privasi', 'legal.privacy')->name('legal.privacy');
Route::view('/kebijakan-cookie', 'legal.cookies')->name('legal.cookies');
Route::view('/kontak', 'legal.contact')->name('legal.contact');
