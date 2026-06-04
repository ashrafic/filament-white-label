<?php

declare(strict_types=1);

use FilamentWhiteLabel\Models\BrandSettings;
use FilamentWhiteLabel\Security\CssSanitizer;

test('model has correct fillable fields', function () {
    $model = new BrandSettings();

    expect($model->getFillable())->toBe([
        'tenant_type',
        'tenant_id',
        'panel_id',
        'metadata',
    ]);
});

test('metadata is cast to array', function () {
    $model = new BrandSettings(['metadata' => ['brand_name' => 'Test']]);

    expect($model->metadata)->toBeArray()
        ->and($model->metadata['brand_name'])->toBe('Test');
});

test('metadata defaults to null when not set', function () {
    $model = new BrandSettings();

    expect($model->metadata)->toBeNull();
});

test('css is sanitized on save', function () {
    $model = new BrandSettings([
        'metadata' => [
            'custom_css' => '<script>alert("xss")</script> body { color: red; }',
        ],
    ]);

    $model->save();

    expect($model->metadata['custom_css'])->not()->toContain('<script>')
        ->and($model->metadata['custom_css'])->toContain('body { color: red; }');
});
