<?php

declare(strict_types=1);

namespace FilamentWhiteLabel;

use FilamentWhiteLabel\Services\BrandResolver;

class FilamentWhiteLabel
{
    public static function brandName(): ?string { return BrandResolver::brandName(); }
    public static function logoUrl(): ?string { return BrandResolver::logoUrl(); }
    public static function faviconUrl(): ?string { return BrandResolver::faviconUrl(); }
    public static function brandLogoHeight(): ?string { return BrandResolver::brandLogoHeight(); }
    public static function colors(): array { return BrandResolver::colors(); }
    public static function fontFamily(): string { return BrandResolver::fontFamily(); }
    public static function customCss(): ?string { return BrandResolver::customCss(); }
    public static function customCssTag(): string { return BrandResolver::customCssTag(); }
    public static function fontLinkTag(): string { return BrandResolver::fontLinkTag(); }
    public static function topNavigation(): bool { return BrandResolver::topNavigation(); }
    public static function sidebarCollapsibleOnDesktop(): bool { return BrandResolver::sidebarCollapsibleOnDesktop(); }
    public static function sidebarFullyCollapsibleOnDesktop(): bool { return BrandResolver::sidebarFullyCollapsibleOnDesktop(); }
    public static function collapsibleNavigationGroups(): bool { return BrandResolver::collapsibleNavigationGroups(); }
    public static function breadcrumbs(): bool { return BrandResolver::breadcrumbs(); }
    public static function unsavedChangesAlerts(): bool { return BrandResolver::unsavedChangesAlerts(); }
    public static function spaMode(): bool { return BrandResolver::spaMode(); }
    public static function databaseNotifications(): bool { return BrandResolver::databaseNotifications(); }
    public static function databaseNotificationsPolling(): ?string { return BrandResolver::databaseNotificationsPolling(); }
    public static function clearCache(): void { BrandResolver::clearCache(); }

    public static function toArray(): array
    {
        return [
            'brand_name' => static::brandName(),
            'logo_url' => static::logoUrl(),
            'favicon_url' => static::faviconUrl(),
            'brand_logo_height' => static::brandLogoHeight(),
            'colors' => static::colors(),
            'font_family' => static::fontFamily(),
            'top_navigation' => static::topNavigation(),
            'breadcrumbs' => static::breadcrumbs(),
            'unsaved_changes_alerts' => static::unsavedChangesAlerts(),
            'spa_mode' => static::spaMode(),
        ];
    }
}
