<?php

declare(strict_types=1);

use FilamentWhiteLabel\Fonts\FontService;

it('returns 50 fonts', function () {
    $fonts = FontService::fontOptions();

    expect($fonts)->toHaveCount(50);
});

it('all fonts have non-empty key and label', function () {
    $fonts = FontService::fontOptions();

    foreach ($fonts as $key => $label) {
        expect($key)->not->toBeEmpty();
        expect($label)->not->toBeEmpty();
    }
});

it('includes Inter as the first font', function () {
    $fonts = FontService::fontOptions();

    expect(array_key_first($fonts))->toBe('Inter');
});

it('returns empty string for Inter font link tag', function () {
    $tag = FontService::linkTag('Inter');

    expect($tag)->toBe('');
});

it('returns valid link tag for Roboto', function () {
    $tag = FontService::linkTag('Roboto');

    expect($tag)->toContain('fonts.googleapis.com');
    expect($tag)->toContain('family=Roboto');
    expect($tag)->toContain('wght@0,400;0,500;0,600;0,700');
    expect($tag)->toContain('display=swap');
});

it('urlencodes font names with spaces', function () {
    $tag = FontService::linkTag('Open Sans');

    expect($tag)->toContain('family=Open+Sans');
});

it('generates correct HTML structure', function () {
    $tag = FontService::linkTag('Roboto');

    expect($tag)->toContain('<link rel="preconnect"');
    expect($tag)->toContain('rel="stylesheet"');
});