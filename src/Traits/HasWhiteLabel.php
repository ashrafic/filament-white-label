<?php

declare(strict_types=1);

namespace FilamentWhiteLabel\Traits;

use FilamentWhiteLabel\Models\WhiteLabelSettings;
use FilamentWhiteLabel\Services\WhiteLabel;
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
                'metadata' => WhiteLabel::defaultMetadata($tenant),
            ]);
        });
    }

    public function whiteLabelSettings(): MorphOne
    {
        return $this->morphOne(WhiteLabelSettings::class, 'tenant');
    }
}
