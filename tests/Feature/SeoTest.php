<?php

use App\Models\Domain;
use App\Models\Setting;
use App\Models\User;

test('home page returns 200 and has dynamic metadata and structured data', function () {
    User::factory()->create(['is_admin' => true]);

    Setting::set('app_name', 'TestMail');
    Setting::set('meta_title', 'Custom TestMail Title');
    Setting::set('meta_description', 'Custom TestMail Description');
    Setting::set('meta_keywords', 'custom, keywords, testmail');

    Domain::create([
        'domain' => 'testmail.example.com',
        'is_active' => true,
        'is_default' => true,
    ]);

    $response = $this->get('/');

    $response->assertStatus(200);
    $response->assertSee('Custom TestMail Title');
    $response->assertSee('Custom TestMail Description');
    $response->assertSee('custom, keywords, testmail');
    $response->assertSee('schema.org');
    $response->assertSee('WebApplication');
    $response->assertSee('WebSite');
});

test('robots.txt returns correct disallow and sitemap url', function () {
    User::factory()->create(['is_admin' => true]);

    $response = $this->get('/robots.txt');

    $response->assertStatus(200);
    $response->assertHeader('Content-Type', 'text/plain; charset=UTF-8');
    $response->assertSee('User-agent: *');
    $response->assertSee('Disallow: /admin');
    $response->assertSee('Disallow: /api');
    $response->assertSee('Sitemap: '.url('/sitemap.xml'), false);
});

test('sitemap.xml returns valid xml structure with static and seo pages', function () {
    User::factory()->create(['is_admin' => true]);

    $response = $this->get('/sitemap.xml');

    $response->assertStatus(200);
    $response->assertHeader('Content-Type', 'application/xml; charset=UTF-8');
    $response->assertSee('<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">', false);
    $response->assertSee(url('/'));
    $response->assertSee(url('/syarat-ketentuan'));
    $response->assertSee(url('/kebijakan-privasi'));
    $response->assertSee(url('/kebijakan-cookie'));
    $response->assertSee(url('/kontak'));
    $response->assertSee(url('/temp-mail'));
    $response->assertSee(url('/temporary-email'));
    $response->assertSee(url('/10-minute-mail'));
    $response->assertSee(url('/disposable-email'));
    $response->assertSee(url('/temporary-email-generator'));
    $response->assertSee('<lastmod>', false);
    $response->assertSee('<changefreq>weekly</changefreq>', false);
});

test('seo landing pages return 200 with appropriate breadcrumb and schema', function () {
    User::factory()->create(['is_admin' => true]);

    $pages = [
        'temp-mail',
        'temporary-email',
        '10-minute-mail',
        'disposable-email',
        'temporary-email-generator',
    ];

    foreach ($pages as $slug) {
        $response = $this->get('/'.$slug);
        $response->assertStatus(200);
        $response->assertSee('schema.org');
        $response->assertSee('BreadcrumbList');
        $response->assertSee('FAQPage');
    }
});

test('legal pages return 200 with schema metadata', function () {
    User::factory()->create(['is_admin' => true]);

    $routes = [
        'syarat-ketentuan',
        'kebijakan-privasi',
        'kebijakan-cookie',
        'kontak',
    ];

    foreach ($routes as $route) {
        $response = $this->get('/'.$route);
        $response->assertStatus(200);
        $response->assertSee('schema.org');
        $response->assertSee('BreadcrumbList');
    }
});
