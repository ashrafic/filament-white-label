<?php

declare(strict_types=1);

namespace FilamentWhiteLabel;

use FilamentWhiteLabel\Services\WhiteLabel;

class FilamentWhiteLabel
{
    public static function brandName(): ?string
    {
        return WhiteLabel::brandName();
    }

    public static function logoUrl(): ?string
    {
        return WhiteLabel::logoUrl();
    }

    public static function faviconUrl(): ?string
    {
        return WhiteLabel::faviconUrl();
    }

    public static function brandLogoHeight(): ?string
    {
        return WhiteLabel::brandLogoHeight();
    }

    public static function darkModeBrandLogoUrl(): ?string
    {
        return WhiteLabel::darkModeBrandLogoUrl();
    }

    public static function colors(): array
    {
        return WhiteLabel::colors();
    }

    public static function fontFamily(): string
    {
        return WhiteLabel::fontFamily();
    }

    public static function customCss(): ?string
    {
        return WhiteLabel::customCss();
    }

    public static function customCssTag(): string
    {
        return WhiteLabel::customCssTag();
    }

    public static function fontLinkTag(): string
    {
        return WhiteLabel::fontLinkTag();
    }

    public static function topbar(): bool
    {
        return WhiteLabel::topbar();
    }

    public static function topNavigation(): bool
    {
        return WhiteLabel::topNavigation();
    }

    public static function sidebarCollapsibleOnDesktop(): bool
    {
        return WhiteLabel::sidebarCollapsibleOnDesktop();
    }

    public static function sidebarFullyCollapsibleOnDesktop(): bool
    {
        return WhiteLabel::sidebarFullyCollapsibleOnDesktop();
    }

    public static function collapsibleNavigationGroups(): bool
    {
        return WhiteLabel::collapsibleNavigationGroups();
    }

    public static function breadcrumbs(): bool
    {
        return WhiteLabel::breadcrumbs();
    }

    public static function unsavedChangesAlerts(): bool
    {
        return WhiteLabel::unsavedChangesAlerts();
    }

    public static function spaMode(): bool
    {
        return WhiteLabel::spaMode();
    }

    public static function databaseNotifications(): bool
    {
        return WhiteLabel::databaseNotifications();
    }

    public static function databaseNotificationsPolling(): ?string
    {
        return WhiteLabel::databaseNotificationsPolling();
    }

    public static function borderRadius(): ?string
    {
        return WhiteLabel::borderRadius();
    }

    public static function inputBorderRadius(): ?string
    {
        return WhiteLabel::inputBorderRadius();
    }

    public static function badgeShape(): ?string
    {
        return WhiteLabel::badgeShape();
    }

    public static function shadowIntensity(): ?string
    {
        return WhiteLabel::shadowIntensity();
    }

    public static function containerWidth(): ?string
    {
        return WhiteLabel::containerWidth();
    }

    public static function sidebarWidth(): ?string
    {
        return WhiteLabel::sidebarWidth();
    }

    public static function headingSize(): ?string
    {
        return WhiteLabel::headingSize();
    }

    public static function navItemSpacing(): ?string
    {
        return WhiteLabel::navItemSpacing();
    }

    public static function fontScale(): ?string
    {
        return WhiteLabel::fontScale();
    }

    public static function formDensity(): ?string
    {
        return WhiteLabel::formDensity();
    }

    public static function tableRowDensity(): ?string
    {
        return WhiteLabel::tableRowDensity();
    }

    public static function modalSize(): ?string
    {
        return WhiteLabel::modalSize();
    }

    public static function transitionSpeed(): ?string
    {
        return WhiteLabel::transitionSpeed();
    }

    public static function footerText(): ?string
    {
        return WhiteLabel::footerText();
    }

    public static function footerLinks(): array
    {
        return WhiteLabel::footerLinks();
    }

    public static function clearCache(): void
    {
        WhiteLabel::clearCache();
    }

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
