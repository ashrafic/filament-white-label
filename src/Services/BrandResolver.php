<?php

declare(strict_types=1);

namespace FilamentWhiteLabel\Services;

use Filament\Facades\Filament;
use FilamentWhiteLabel\Fonts\FontService;
use FilamentWhiteLabel\Models\BrandSettings;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class BrandResolver
{
    public static function resolve(): ?BrandSettings
    {
        if (! config('filament-white-label.enabled', true)) {
            return null;
        }

        $tenant = Filament::getTenant();

        if ($tenant) {
            $cached = static::resolveFromCache($tenant);
            if ($cached) {
                return $cached;
            }
            return static::resolveFromDatabase($tenant);
        }

        return static::resolveGlobal();
    }

    public static function brandName(): ?string
    {
        return static::resolve()?->brand_name
            ?? config('filament-white-label.defaults.brand_name');
    }

    public static function logoUrl(): ?string
    {
        $settings = static::resolve();

        if ($settings?->logo_path) {
            return Storage::disk(config('filament-white-label.disk', 'public'))
                ->url($settings->logo_path);
        }

        return config('filament-white-label.defaults.logo');
    }

    public static function faviconUrl(): ?string
    {
        $settings = static::resolve();

        if ($settings?->favicon_path) {
            return Storage::disk(config('filament-white-label.disk', 'public'))
                ->url($settings->favicon_path);
        }

        return config('filament-white-label.defaults.favicon');
    }

    public static function colors(): array
    {
        $defaults = config('filament-white-label.defaults.colors', []);
        $settings = static::resolve();

        if (! $settings?->colors) {
            return $defaults;
        }

        return array_merge($defaults, $settings->colors);
    }

    public static function fontFamily(): string
    {
        return static::resolve()?->font_family
            ?? config('filament-white-label.defaults.font_family', 'Inter');
    }

    public static function customCss(): ?string
    {
        if (config('filament-white-label.security.disable_custom_css', false)) {
            return null;
        }

        return static::resolve()?->custom_css;
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

    public static function clearCache(?Model $tenant = null): void
    {
        if ($tenant) {
            Cache::forget(static::cacheKey($tenant));
        } else {
            Cache::forget('filament-white-label:global');
        }
    }

    protected static function resolveFromCache(?Model $tenant): ?BrandSettings
    {
        $ttl = config('filament-white-label.cache_ttl', 300);

        if ($ttl <= 0) {
            return null;
        }

        return Cache::get(static::cacheKey($tenant));
    }

    protected static function resolveFromDatabase(?Model $tenant): ?BrandSettings
    {
        $settings = BrandSettings::query()
            ->where('tenant_type', $tenant->getMorphClass())
            ->where('tenant_id', $tenant->getKey())
            ->first();

        if ($settings) {
            $ttl = config('filament-white-label.cache_ttl', 300);
            if ($ttl > 0) {
                Cache::put(static::cacheKey($tenant), $settings, $ttl);
            }
            return $settings;
        }

        return null;
    }

    protected static function cacheKey(?Model $tenant): string
    {
        if ($tenant) {
            return 'filament-white-label:tenant:' . $tenant->getMorphClass() . ':' . $tenant->getKey();
        }

        return 'filament-white-label:global';
    }

    protected static function resolveGlobal(): ?BrandSettings
    {
        $cached = Cache::get('filament-white-label:global');

        if ($cached) {
            return $cached;
        }

        $settings = BrandSettings::query()
            ->whereNull('tenant_type')
            ->whereNull('tenant_id')
            ->first();

        if ($settings) {
            $ttl = config('filament-white-label.cache_ttl', 300);
            if ($ttl > 0) {
                Cache::put('filament-white-label:global', $settings, $ttl);
            }
        }

        return $settings;
    }
}