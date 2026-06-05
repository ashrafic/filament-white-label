<?php

declare(strict_types=1);

namespace FilamentWhiteLabel\Services;

use Filament\Facades\Filament;
use FilamentWhiteLabel\Fonts\FontService;
use FilamentWhiteLabel\Models\WhiteLabelSettings;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class WhiteLabel
{
    public static function resolve(): ?WhiteLabelSettings
    {
        if (! config('filament-white-label.enabled', true)) {
            return null;
        }

        $tenant = Filament::getTenant();
        $panelId = Filament::getCurrentPanel()?->getId();

        if ($tenant) {
            $cached = static::resolveFromCache($tenant, $panelId);
            if ($cached) {
                return $cached;
            }
            return static::resolveFromDatabase($tenant, $panelId);
        }

        return static::resolveGlobal($panelId);
    }

    public static function brandName(): ?string
    {
        return static::resolve()?->metadata['brand_name']
            ?? config('filament-white-label.defaults.brand_name');
    }

    public static function logoUrl(): ?string
    {
        $settings = static::resolve();

        if ($settings && ! empty($settings->metadata['logo_path'])) {
            return Storage::disk(config('filament-white-label.disk', 'public'))
                ->url($settings->metadata['logo_path']);
        }

        return config('filament-white-label.defaults.logo');
    }

    public static function faviconUrl(): ?string
    {
        $settings = static::resolve();

        if ($settings && ! empty($settings->metadata['favicon_path'])) {
            return Storage::disk(config('filament-white-label.disk', 'public'))
                ->url($settings->metadata['favicon_path']);
        }

        return config('filament-white-label.defaults.favicon');
    }

    public static function brandLogoHeight(): ?string
    {
        return static::resolve()?->metadata['brand_logo_height']
            ?? config('filament-white-label.defaults.brand_logo_height');
    }

    public static function colors(): array
    {
        $defaults = config('filament-white-label.defaults.colors', []);
        $settings = static::resolve();

        if (! $settings || empty($settings->metadata['colors'])) {
            return $defaults;
        }

        return array_merge($defaults, $settings->metadata['colors']);
    }

    public static function fontFamily(): string
    {
        return static::resolve()?->metadata['font_family']
            ?? config('filament-white-label.defaults.font_family', 'Inter');
    }

    public static function customCss(): ?string
    {
        if (config('filament-white-label.security.disable_custom_css', false)) {
            return null;
        }

        return static::resolve()?->metadata['custom_css'] ?? null;
    }

    public static function customCssTag(): string
    {
        $css = static::customCss();

        if (blank($css)) {
            return '';
        }

        return '<style>' . e($css) . '</style>';
    }

    public static function fontLinkTag(): string
    {
        if (! config('filament-white-label.fonts.enabled', true)) {
            return '';
        }

        $family = static::fontFamily();

        if ($family === 'Inter') {
            return '';
        }

        return FontService::linkTag($family);
    }

    // -- Layout Settings --

    public static function topNavigation(): bool
    {
        return static::boolOrDefault('top_navigation', false);
    }

    public static function sidebarCollapsibleOnDesktop(): bool
    {
        return static::boolOrDefault('sidebar_collapsible_on_desktop', false);
    }

    public static function sidebarFullyCollapsibleOnDesktop(): bool
    {
        return static::boolOrDefault('sidebar_fully_collapsible_on_desktop', false);
    }

    public static function collapsibleNavigationGroups(): bool
    {
        return static::boolOrDefault('collapsible_navigation_groups', true);
    }

    public static function breadcrumbs(): bool
    {
        return static::boolOrDefault('breadcrumbs', true);
    }

    // -- Advanced Settings --

    public static function unsavedChangesAlerts(): bool
    {
        return static::boolOrDefault('unsaved_changes_alerts', false);
    }

    public static function spaMode(): bool
    {
        return static::boolOrDefault('spa_mode', false);
    }

    public static function databaseNotifications(): bool
    {
        return static::boolOrDefault('database_notifications', false);
    }

    public static function databaseNotificationsPolling(): ?string
    {
        return static::resolve()?->metadata['database_notifications_polling']
            ?? config('filament-white-label.defaults.database_notifications_polling');
    }

    public static function clearCache(?Model $tenant = null, ?string $panelId = null): void
    {
        Cache::forget(static::cacheKey($tenant, $panelId));
    }

    // -- Internal --

    protected static function boolOrDefault(string $key, bool $fallback): bool
    {
        $settings = static::resolve();

        if ($settings && array_key_exists($key, $settings->metadata)) {
            return (bool) $settings->metadata[$key];
        }

        return $fallback;
    }

    protected static function resolveFromCache(?Model $tenant, ?string $panelId): ?WhiteLabelSettings
    {
        $ttl = config('filament-white-label.cache_ttl', 300);

        if ($ttl <= 0) {
            return null;
        }

        return Cache::get(static::cacheKey($tenant, $panelId));
    }

    protected static function resolveFromDatabase(?Model $tenant, ?string $panelId): ?WhiteLabelSettings
    {
        $settings = WhiteLabelSettings::query()
            ->where('tenant_type', $tenant->getMorphClass())
            ->where('tenant_id', $tenant->getKey())
            ->where(fn ($q) => $q->where('panel_id', $panelId)->orWhereNull('panel_id'))
            ->orderByRaw('panel_id IS NOT NULL DESC')
            ->first();

        if ($settings) {
            $ttl = config('filament-white-label.cache_ttl', 300);
            if ($ttl > 0) {
                Cache::put(static::cacheKey($tenant, $panelId), $settings, $ttl);
            }
            return $settings;
        }

        return null;
    }

    protected static function cacheKey(?Model $tenant, ?string $panelId): string
    {
        if ($tenant) {
            return 'filament-white-label:tenant:' . $tenant->getMorphClass() . ':' . $tenant->getKey() . ':panel:' . ($panelId ?? 'null');
        }

        return 'filament-white-label:global:panel:' . ($panelId ?? 'null');
    }

    protected static function resolveGlobal(?string $panelId): ?WhiteLabelSettings
    {
        $cacheKey = static::cacheKey(null, $panelId);
        $cached = Cache::get($cacheKey);

        if ($cached) {
            return $cached;
        }

        $settings = WhiteLabelSettings::query()
            ->whereNull('tenant_type')
            ->whereNull('tenant_id')
            ->where(fn ($q) => $q->where('panel_id', $panelId)->orWhereNull('panel_id'))
            ->orderByRaw('panel_id IS NOT NULL DESC')
            ->first();

        if ($settings) {
            $ttl = config('filament-white-label.cache_ttl', 300);
            if ($ttl > 0) {
                Cache::put($cacheKey, $settings, $ttl);
            }
        }

        return $settings;
    }
}
