<?php

declare(strict_types=1);

namespace FilamentWhiteLabel\Traits;

use FilamentWhiteLabel\Models\BrandSettings;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait HasBrandSettings
{
    public static function bootHasBrandSettings(): void
    {
        static::created(function (Model $tenant) {
            if (! config('filament-white-label.enabled', true)) {
                return;
            }

            $tenant->brandSettings()->create([
                'brand_name' => $tenant->name ?? config('app.name'),
                'font_family' => config('filament-white-label.defaults.font_family', 'Inter'),
                'colors' => config('filament-white-label.defaults.colors'),
            ]);
        });
    }

    public function brandSettings(): MorphOne
    {
        return $this->morphOne(BrandSettings::class, 'tenant');
    }
}