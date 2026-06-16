<?php

declare(strict_types=1);

namespace FilamentWhiteLabel\Services;

use Filament\Facades\Filament;
use Filament\Support\Colors\Color;
use Filament\Support\Colors\ColorManager;
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

    public static function darkModeBrandLogoUrl(): ?string
    {
        $settings = static::resolve();

        if ($settings && ! empty($settings->metadata['dark_mode_logo_path'])) {
            return Storage::disk(config('filament-white-label.disk', 'public'))
                ->url($settings->metadata['dark_mode_logo_path']);
        }

        return config('filament-white-label.defaults.dark_mode_logo');
    }

    public static function colors(): array
    {
        $settings = static::resolve();

        if (! $settings || empty($settings->metadata['colors'])) {
            return [];
        }

        return array_filter(
            $settings->metadata['colors'],
            fn ($v) => filled($v),
        );
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
        $themeCss = static::generatedThemeCss();
        $userCss = static::customCss();

        if (blank($themeCss) && blank($userCss)) {
            return '';
        }

        $combined = $themeCss;

        if (filled($userCss)) {
            $combined .= "\n".$userCss;
        }

        return '<style>'.e($combined).'</style>';
    }

    public static function generatedThemeCss(): string
    {
        $settings = static::resolve();

        if (! $settings) {
            return '';
        }

        $css = '';
        $meta = $settings->metadata;

        $css .= static::borderRadiusCss($meta['border_radius'] ?? null);
        $css .= static::inputBorderRadiusCss($meta['input_border_radius'] ?? null);
        $css .= static::badgeShapeCss($meta['badge_shape'] ?? null);
        $css .= static::shadowIntensityCss($meta['shadow_intensity'] ?? null);
        $css .= static::containerWidthCss($meta['container_width'] ?? null);
        $css .= static::sidebarWidthCss($meta['sidebar_width'] ?? null);
        $css .= static::headingSizeCss($meta['heading_size'] ?? null);
        $css .= static::navItemSpacingCss($meta['nav_item_spacing'] ?? null);
        $css .= static::fontScaleCss($meta['font_scale'] ?? null);
        $css .= static::formDensityCss($meta['form_density'] ?? null);
        $css .= static::tableRowDensityCss($meta['table_row_density'] ?? null);
        $css .= static::modalSizeCss($meta['modal_size'] ?? null);
        $css .= static::transitionSpeedCss($meta['transition_speed'] ?? null);

        return $css;
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

    public static function topbar(): bool
    {
        return static::boolOrDefault('topbar');
    }

    public static function topNavigation(): bool
    {
        return static::boolOrDefault('top_navigation');
    }

    public static function sidebarCollapsibleOnDesktop(): bool
    {
        return static::boolOrDefault('sidebar_collapsible_on_desktop');
    }

    public static function sidebarFullyCollapsibleOnDesktop(): bool
    {
        return static::boolOrDefault('sidebar_fully_collapsible_on_desktop');
    }

    public static function collapsibleNavigationGroups(): bool
    {
        return static::boolOrDefault('collapsible_navigation_groups');
    }

    public static function breadcrumbs(): bool
    {
        return static::boolOrDefault('breadcrumbs');
    }

    // -- Advanced Settings --

    public static function unsavedChangesAlerts(): bool
    {
        return static::boolOrDefault('unsaved_changes_alerts');
    }

    public static function spaMode(): bool
    {
        return static::boolOrDefault('spa_mode');
    }

    public static function databaseNotifications(): bool
    {
        return static::boolOrDefault('database_notifications');
    }

    public static function databaseNotificationsPolling(): ?string
    {
        return static::resolve()?->metadata['database_notifications_polling']
            ?? config('filament-white-label.defaults.database_notifications_polling');
    }

    // -- Footer --

    public static function footerText(): ?string
    {
        return static::resolve()?->metadata['footer_text']
            ?? config('filament-white-label.defaults.footer_text');
    }

    public static function footerLinks(): array
    {
        return static::resolve()?->metadata['footer_links']
            ?? config('filament-white-label.defaults.footer_links', []);
    }

    public static function footerHtml(): string
    {
        $text = static::footerText();
        $links = static::footerLinks();

        if (blank($text) && empty($links)) {
            return '';
        }

        $html = '<footer class="fi-footer fi-footer-wl" style="padding:1rem 1.5rem;text-align:center;border-top:1px solid rgba(0,0,0,.08);font-size:.875rem;color:rgba(0,0,0,.45)">';

        if (filled($text)) {
            $html .= '<span class="fi-footer-text">'.e($text).'</span>';
        }

        if (! empty($links)) {
            $html .= '<nav class="fi-footer-links" style="margin-top:.5rem;display:flex;justify-content:center;gap:1rem;flex-wrap:wrap">';
            foreach ($links as $link) {
                if (! empty($link['label']) && ! empty($link['url'])) {
                    $html .= '<a href="'.e($link['url']).'" style="color:rgba(0,0,0,.55);text-decoration:none" target="_blank" rel="noopener">'.e($link['label']).'</a>';
                }
            }
            $html .= '</nav>';
        }

        $html .= '</footer>';

        return $html;
    }

    // -- CSS Theme Getters --

    public static function borderRadius(): ?string
    {
        return static::resolve()?->metadata['border_radius']
            ?? config('filament-white-label.defaults.border_radius');
    }

    public static function inputBorderRadius(): ?string
    {
        return static::resolve()?->metadata['input_border_radius']
            ?? config('filament-white-label.defaults.input_border_radius');
    }

    public static function badgeShape(): ?string
    {
        return static::resolve()?->metadata['badge_shape']
            ?? config('filament-white-label.defaults.badge_shape');
    }

    public static function shadowIntensity(): ?string
    {
        return static::resolve()?->metadata['shadow_intensity']
            ?? config('filament-white-label.defaults.shadow_intensity');
    }

    public static function containerWidth(): ?string
    {
        return static::resolve()?->metadata['container_width']
            ?? config('filament-white-label.defaults.container_width');
    }

    public static function sidebarWidth(): ?string
    {
        return static::resolve()?->metadata['sidebar_width']
            ?? config('filament-white-label.defaults.sidebar_width');
    }

    public static function headingSize(): ?string
    {
        return static::resolve()?->metadata['heading_size']
            ?? config('filament-white-label.defaults.heading_size');
    }

    public static function navItemSpacing(): ?string
    {
        return static::resolve()?->metadata['nav_item_spacing']
            ?? config('filament-white-label.defaults.nav_item_spacing');
    }

    public static function fontScale(): ?string
    {
        return static::resolve()?->metadata['font_scale']
            ?? config('filament-white-label.defaults.font_scale');
    }

    public static function formDensity(): ?string
    {
        return static::resolve()?->metadata['form_density']
            ?? config('filament-white-label.defaults.form_density');
    }

    public static function tableRowDensity(): ?string
    {
        return static::resolve()?->metadata['table_row_density']
            ?? config('filament-white-label.defaults.table_row_density');
    }

    public static function modalSize(): ?string
    {
        return static::resolve()?->metadata['modal_size']
            ?? config('filament-white-label.defaults.modal_size');
    }

    public static function transitionSpeed(): ?string
    {
        return static::resolve()?->metadata['transition_speed']
            ?? config('filament-white-label.defaults.transition_speed');
    }

    // -- CSS Generation Helpers --

    protected static function borderRadiusCss(?string $value): string
    {
        $map = [
            'none' => '0',
            'small' => '0.25rem',
            'medium' => '0.5rem',
            'large' => '0.75rem',
            'pill' => '9999px',
        ];

        if (! $value || $value === 'default' || ! isset($map[$value])) {
            return '';
        }

        return ".fi-btn,.fi-btn-group,.fi-input-wrp,.fi-select-input,.fi-checkbox-input,.fi-section,.fi-ta,.fi-ta-ctn,.fi-wi-stats-overview-stat,.fi-modal-window,.fi-dropdown-panel,.fi-dropdown-list-item,.fi-badge,.fi-tabs,.fi-tabs-item,.fi-active,.fi-sc-tabs.fi-contained,.fi-callout,.fi-fieldset,.fi-pagination,.fi-avatar,.fi-empty-state,.fi-icon-button,.fi-sidebar-item-btn,.fi-fo-table-repeater{border-radius:{$map[$value]}!important}\n"
            .".fi-fo-table-repeater>table>thead>tr>th:first-of-type{border-top-left-radius:{$map[$value]}!important}\n"
            .".fi-fo-table-repeater>table>thead>tr>th:last-of-type{border-top-right-radius:{$map[$value]}!important}\n";
    }

    protected static function inputBorderRadiusCss(?string $value): string
    {
        $map = [
            'none' => '0',
            'small' => '0.25rem',
            'medium' => '0.5rem',
            'large' => '0.75rem',
            'pill' => '9999px',
        ];

        if (! $value || $value === 'default' || $value === 'inherit' || ! isset($map[$value])) {
            return '';
        }

        return ".fi-input-wrp,.fi-select-input{border-radius:{$map[$value]}!important}\n";
    }

    protected static function badgeShapeCss(?string $value): string
    {
        if (! $value || $value === 'default') {
            return '';
        }

        if ($value === 'sharp') {
            return ".fi-badge{border-radius:0!important}\n";
        }

        if ($value === 'pill') {
            return ".fi-badge{border-radius:9999px!important;padding-left:0.75rem!important;padding-right:0.75rem!important}\n";
        }

        if ($value === 'rounded') {
            return ".fi-badge{border-radius:0.25rem!important}\n";
        }

        return '';
    }

    protected static function shadowIntensityCss(?string $value): string
    {
        if (! $value || $value === 'default') {
            return '';
        }

        if ($value === 'none') {
            return ".fi-section,.fi-dropdown-panel{box-shadow:none!important}\n";
        }

        if ($value === 'subtle') {
            return ".fi-section,.fi-dropdown-panel{box-shadow:0 1px 2px rgba(0,0,0,.05)!important}\n";
        }

        if ($value === 'pronounced') {
            return ".fi-section,.fi-dropdown-panel{box-shadow:0 4px 12px rgba(0,0,0,.15)!important}\n";
        }

        return '';
    }

    protected static function containerWidthCss(?string $value): string
    {
        if (blank($value)) {
            return '';
        }

        if ($value === 'full') {
            return ".fi-main{max-width:none!important}\n";
        }

        return ".fi-main{max-width:{$value}!important}\n";
    }

    protected static function sidebarWidthCss(?string $value): string
    {
        if (blank($value)) {
            return '';
        }

        return ".fi-sidebar.fi-sidebar-open{width:{$value}!important}\n";
    }

    protected static function headingSizeCss(?string $value): string
    {
        $map = [
            'small' => '1.25rem',
            'large' => '2rem',
        ];

        if (! $value || $value === 'default' || ! isset($map[$value])) {
            return '';
        }

        return ".fi-header-heading{font-size:{$map[$value]}!important}\n";
    }

    protected static function navItemSpacingCss(?string $value): string
    {
        if (! $value || $value === 'default') {
            return '';
        }

        if ($value === 'compact') {
            return ".fi-sidebar-item a,.fi-sidebar-item button{padding-top:.25rem!important;padding-bottom:.25rem!important}\n";
        }

        if ($value === 'spacious') {
            return ".fi-sidebar-item a,.fi-sidebar-item button{padding-top:.75rem!important;padding-bottom:.75rem!important}\n";
        }

        return '';
    }

    protected static function fontScaleCss(?string $value): string
    {
        if (blank($value)) {
            return '';
        }

        return "html{font-size:{$value}!important}\n";
    }

    protected static function formDensityCss(?string $value): string
    {
        if (! $value || $value === 'default') {
            return '';
        }

        if ($value === 'compact') {
            return ".fi-section{padding:0.75rem!important}.fi-fo-field-wrp{padding-top:0!important;padding-bottom:0!important}\n";
        }

        if ($value === 'spacious') {
            return ".fi-section{padding:1.5rem!important}.fi-fo-field-wrp{padding-top:0.75rem!important;padding-bottom:0.75rem!important}\n";
        }

        return '';
    }

    protected static function tableRowDensityCss(?string $value): string
    {
        if (! $value || $value === 'default') {
            return '';
        }

        if ($value === 'compact') {
            return ".fi-ta-table td{padding-top:.25rem!important;padding-bottom:.25rem!important}\n";
        }

        if ($value === 'spacious') {
            return ".fi-ta-table td{padding-top:1rem!important;padding-bottom:1rem!important}\n";
        }

        return '';
    }

    protected static function modalSizeCss(?string $value): string
    {
        $map = [
            'small' => '480px',
            'medium' => '640px',
            'large' => '800px',
            'extra-large' => '1024px',
        ];

        if (! $value || $value === 'default' || ! isset($map[$value])) {
            return '';
        }

        return ".fi-modal-window{max-width:{$map[$value]}!important}\n";
    }

    protected static function transitionSpeedCss(?string $value): string
    {
        $map = [
            'none' => '0s',
            'fast' => '0.1s',
            'slow' => '0.3s',
        ];

        if (! $value || $value === 'default' || ! isset($map[$value])) {
            return '';
        }

        return ".fi-btn,.fi-dropdown,.fi-modal,.fi-sidebar{transition-duration:{$map[$value]}!important}\n";
    }

    public static function defaultColors(): array
    {
        $filamentDefaults = ColorManager::DEFAULT_COLORS;

        $roles = ['primary', 'secondary', 'danger', 'warning', 'success', 'info'];

        $colors = [];

        foreach ($roles as $role) {
            if (isset($filamentDefaults[$role])) {
                $colors[$role] = Color::convertToHex($filamentDefaults[$role][500]);
            }
        }

        $colors['secondary'] ??= Color::convertToHex(Color::Zinc[500]);

        return $colors;
    }

    public static function clearCache(?Model $tenant = null, ?string $panelId = null): void
    {
        Cache::forget(static::cacheKey($tenant, $panelId));
    }

    // -- Internal --

    private const FILAMENT_DEFAULTS = [
        'topbar' => true,
        'top_navigation' => false,
        'sidebar_collapsible_on_desktop' => false,
        'sidebar_fully_collapsible_on_desktop' => false,
        'collapsible_navigation_groups' => true,
        'breadcrumbs' => true,
        'unsaved_changes_alerts' => false,
        'spa_mode' => false,
        'database_notifications' => false,
    ];

    protected static function boolOrDefault(string $key): bool
    {
        $settings = static::resolve();

        if ($settings && array_key_exists($key, $settings->metadata)) {
            return (bool) $settings->metadata[$key];
        }

        $configValue = config("filament-white-label.defaults.{$key}");

        if ($configValue !== null) {
            return (bool) $configValue;
        }

        return static::FILAMENT_DEFAULTS[$key] ?? false;
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
            return 'filament-white-label:tenant:'.$tenant->getMorphClass().':'.$tenant->getKey().':panel:'.($panelId ?? 'null');
        }

        return 'filament-white-label:global:panel:'.($panelId ?? 'null');
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
