<?php

declare(strict_types=1);

use FilamentWhiteLabel\Models\BrandSettings;

it('has correct fillable fields', function () {
    $model = new BrandSettings();

    expect($model->getFillable())->toBe([
        'tenant_type',
        'tenant_id',
        'brand_name',
        'logo_path',
        'favicon_path',
        'font_family',
        'custom_css',
        'email_from_address',
        'email_from_name',
        'metadata',
    ]);
});

it('casts colors to array', function () {
    $model = new BrandSettings();

    expect($model->getCasts())->toHaveKey('colors', 'array');
});

it('casts metadata to array', function () {
    $model = new BrandSettings();

    expect($model->getCasts())->toHaveKey('metadata', 'array');
});

it('has tenant morphTo relationship', function () {
    $model = new BrandSettings();

    expect(method_exists($model, 'tenant'))->toBeTrue();
});