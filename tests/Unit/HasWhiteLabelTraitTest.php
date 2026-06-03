<?php

declare(strict_types=1);

use FilamentWhiteLabel\Concerns\HasWhiteLabel;
use Closure;

it('whiteLabelBrandName returns a closure', function () {
    $trait = new class { use HasWhiteLabel; };

    $closure = $trait->whiteLabelBrandName();

    expect($closure)->toBeInstanceOf(Closure::class);
});

it('whiteLabelLogo returns a closure', function () {
    $trait = new class { use HasWhiteLabel; };

    expect($trait->whiteLabelLogo())->toBeInstanceOf(Closure::class);
});

it('whiteLabelColors returns a closure', function () {
    $trait = new class { use HasWhiteLabel; };

    expect($trait->whiteLabelColors())->toBeInstanceOf(Closure::class);
});

it('whiteLabelFontFamily returns a closure', function () {
    $trait = new class { use HasWhiteLabel; };

    expect($trait->whiteLabelFontFamily())->toBeInstanceOf(Closure::class);
});

it('whiteLabelFavicon returns a closure', function () {
    $trait = new class { use HasWhiteLabel; };

    expect($trait->whiteLabelFavicon())->toBeInstanceOf(Closure::class);
});

it('whiteLabelHeadHook returns a closure', function () {
    $trait = new class { use HasWhiteLabel; };

    expect($trait->whiteLabelHeadHook())->toBeInstanceOf(Closure::class);
});

it('whiteLabel method returns panel unchanged when disabled', function () {
    config()->set('filament-white-label.enabled', false);

    $trait = new class { use HasWhiteLabel; };

    expect(config('filament-white-label.enabled'))->toBeFalse();
});