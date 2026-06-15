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
                'metadata' => [
                    'brand_name' => $tenant->name ?? config('app.name'),
                    'font_family' => config('filament-white-label.defaults.font_family', 'Inter'),
                    'colors' => WhiteLabel::defaultColors(),
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
                    'border_radius' => config('filament-white-label.defaults.border_radius', 'default'),
                    'input_border_radius' => config('filament-white-label.defaults.input_border_radius'),
                    'badge_shape' => config('filament-white-label.defaults.badge_shape', 'default'),
                    'shadow_intensity' => config('filament-white-label.defaults.shadow_intensity', 'default'),
                    'container_width' => config('filament-white-label.defaults.container_width'),
                    'sidebar_width' => config('filament-white-label.defaults.sidebar_width'),
                    'heading_size' => config('filament-white-label.defaults.heading_size', 'default'),
                    'nav_item_spacing' => config('filament-white-label.defaults.nav_item_spacing', 'default'),
                    'font_scale' => config('filament-white-label.defaults.font_scale'),
                    'form_density' => config('filament-white-label.defaults.form_density', 'default'),
                    'table_row_density' => config('filament-white-label.defaults.table_row_density', 'default'),
                    'modal_size' => config('filament-white-label.defaults.modal_size', 'default'),
                    'transition_speed' => config('filament-white-label.defaults.transition_speed', 'default'),
                ],
            ]);
        });
    }

    public function whiteLabelSettings(): MorphOne
    {
        return $this->morphOne(WhiteLabelSettings::class, 'tenant');
    }
}
