<?php

declare(strict_types=1);

use Filament\Facades\Filament;
use FilamentWhiteLabel\Models\WhiteLabelSettings;
use FilamentWhiteLabel\Services\WhiteLabel;

test('boolOrDefault returns config default when key absent from metadata', function () {
    config()->set('filament-white-label.defaults.top_navigation', false);

    $settings = new WhiteLabelSettings(['metadata' => []]);
    $settings->save();

    expect($settings->metadata)->not->toHaveKey('top_navigation');
});

test('boolOrDefault returns DB value when key present in metadata', function () {
    config()->set('filament-white-label.defaults.top_navigation', false);

    $settings = new WhiteLabelSettings(['metadata' => ['top_navigation' => true]]);

    expect($settings->metadata['top_navigation'])->toBeTrue();
});

test('boolOrDefault returns false when explicitly set false in metadata', function () {
    config()->set('filament-white-label.defaults.breadcrumbs', true);

    $settings = new WhiteLabelSettings(['metadata' => ['breadcrumbs' => false]]);

    expect($settings->metadata['breadcrumbs'])->toBeFalse();
});
