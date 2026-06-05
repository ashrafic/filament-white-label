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
                    'topbar' => config('filament-white-label.defaults.topbar', true),
                    'top_navigation' => config('filament-white-label.defaults.top_navigation', false),
                    'sidebar_collapsible_on_desktop' => config('filament-white-label.defaults.sidebar_collapsible_on_desktop', false),
                    'sidebar_fully_collapsible_on_desktop' => config('filament-white-label.defaults.sidebar_fully_collapsible_on_desktop', false),
                    'collapsible_navigation_groups' => config('filament-white-label.defaults.collapsible_navigation_groups', true),
                    'breadcrumbs' => config('filament-white-label.defaults.breadcrumbs', true),
                    'unsaved_changes_alerts' => config('filament-white-label.defaults.unsaved_changes_alerts', false),
                    'spa_mode' => config('filament-white-label.defaults.spa_mode', false),
                    'database_notifications' => config('filament-white-label.defaults.database_notifications', false),
                    'database_notifications_polling' => config('filament-white-label.defaults.database_notifications_polling', '30s'),
                ],
            ]);
        });
    }

    public function whiteLabelSettings(): MorphOne
    {
        return $this->morphOne(WhiteLabelSettings::class, 'tenant');
    }
}