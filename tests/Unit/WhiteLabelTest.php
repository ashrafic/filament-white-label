<?php

declare(strict_types=1);

use FilamentWhiteLabel\Models\WhiteLabelSettings;

test('metadata stores and retrieves settings correctly', function () {
    $settings = new WhiteLabelSettings([
        'metadata' => [
            'top_navigation' => true,
            'breadcrumbs' => false,
            'spa_mode' => true,
            'database_notifications_polling' => '60s',
        ],
    ]);
    $settings->save();

    expect($settings->metadata['top_navigation'])->toBeTrue();
    expect($settings->metadata['breadcrumbs'])->toBeFalse();
    expect($settings->metadata['spa_mode'])->toBeTrue();
    expect($settings->metadata['database_notifications_polling'])->toBe('60s');
});

test('absent metadata keys return null', function () {
    $settings = new WhiteLabelSettings(['metadata' => []]);

    expect($settings->metadata['top_navigation'] ?? null)->toBeNull();
    expect($settings->metadata['does_not_exist'] ?? null)->toBeNull();
});

test('config defaults are set correctly', function () {
    expect(config('filament-white-label.defaults.top_navigation'))->toBeFalse();
    expect(config('filament-white-label.defaults.breadcrumbs'))->toBeTrue();
    expect(config('filament-white-label.defaults.spa_mode'))->toBeFalse();
    expect(config('filament-white-label.defaults.topbar'))->toBeTrue();
    expect(config('filament-white-label.defaults.collapsible_navigation_groups'))->toBeTrue();
});
