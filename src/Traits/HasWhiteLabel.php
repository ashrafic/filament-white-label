<?php

declare(strict_types=1);

namespace FilamentWhiteLabel\Traits;

use FilamentWhiteLabel\Models\WhiteLabelSettings;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait HasWhiteLabel
{
    public static function bootHasWhiteLabel(): void
    {
        static::created(function (Model $tenant) {
            if (! config('filament-white-label.enabled', true)) {
                return;
            }

            $tenant->whiteLabelSettings()->create([
                'metadata' => [
                    'brand_name' => $tenant->name ?? config('app.name'),
                    'font_family' => config('filament-white-label.defaults.font_family', 'Inter'),
                    'colors' => config('filament-white-label.defaults.colors'),
                ],
            ]);
        });
    }

    public function whiteLabelSettings(): MorphOne
    {
        return $this->morphOne(WhiteLabelSettings::class, 'tenant');
    }
}