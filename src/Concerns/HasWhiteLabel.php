<?php

declare(strict_types=1);

namespace FilamentWhiteLabel\Concerns;

use Closure;
use Filament\Panel;
use FilamentWhiteLabel\Services\BrandResolver;

trait HasWhiteLabel
{
    public function whiteLabelBrandName(): Closure
    {
        return fn (): ?string => BrandResolver::brandName();
    }

    public function whiteLabelLogo(): Closure
    {
        return fn (): ?string => BrandResolver::logoUrl();
    }

    public function whiteLabelBrandLogoHeight(): Closure
    {
        return fn (): ?string => BrandResolver::brandLogoHeight();
    }

    public function whiteLabelFavicon(): Closure
    {
        return fn (): ?string => BrandResolver::faviconUrl();
    }

    public function whiteLabelColors(): Closure
    {
        return fn (): array => BrandResolver::colors();
    }

    public function whiteLabelFontFamily(): Closure
    {
        return fn (): string => BrandResolver::fontFamily();
    }

    public function whiteLabelHeadHook(): Closure
    {
        return fn (): string => BrandResolver::fontLinkTag() . BrandResolver::customCssTag();
    }

    public function whiteLabelTopNavigation(): Closure
    {
        return fn (): bool => BrandResolver::topNavigation();
    }

    public function whiteLabelSidebarCollapsibleOnDesktop(): Closure
    {
        return fn (): bool => BrandResolver::sidebarCollapsibleOnDesktop();
    }

    public function whiteLabelSidebarFullyCollapsibleOnDesktop(): Closure
    {
        return fn (): bool => BrandResolver::sidebarFullyCollapsibleOnDesktop();
    }

    public function whiteLabelCollapsibleNavigationGroups(): Closure
    {
        return fn (): bool => BrandResolver::collapsibleNavigationGroups();
    }

    public function whiteLabelBreadcrumbs(): Closure
    {
        return fn (): bool => BrandResolver::breadcrumbs();
    }

    public function whiteLabelUnsavedChangesAlerts(): Closure
    {
        return fn (): bool => BrandResolver::unsavedChangesAlerts();
    }

    public function whiteLabelSpaMode(): Closure
    {
        return fn (): bool => BrandResolver::spaMode();
    }

    public function whiteLabelDatabaseNotifications(): Closure
    {
        return fn (): bool => BrandResolver::databaseNotifications();
    }

    public function whiteLabelDatabaseNotificationsPolling(): Closure
    {
        return fn (): ?string => BrandResolver::databaseNotificationsPolling();
    }

    public function whiteLabel(Panel $panel): Panel
    {
        if (! config('filament-white-label.enabled', true)) {
            return $panel;
        }

        return $panel
            ->brandName($this->whiteLabelBrandName())
            ->brandLogo($this->whiteLabelLogo())
            ->brandLogoHeight($this->whiteLabelBrandLogoHeight())
            ->favicon($this->whiteLabelFavicon())
            ->colors($this->whiteLabelColors())
            ->font($this->whiteLabelFontFamily())
            ->topNavigation($this->whiteLabelTopNavigation())
            ->sidebarCollapsibleOnDesktop($this->whiteLabelSidebarCollapsibleOnDesktop())
            ->sidebarFullyCollapsibleOnDesktop($this->whiteLabelSidebarFullyCollapsibleOnDesktop())
            ->collapsibleNavigationGroups($this->whiteLabelCollapsibleNavigationGroups())
            ->breadcrumbs($this->whiteLabelBreadcrumbs())
            ->unsavedChangesAlerts($this->whiteLabelUnsavedChangesAlerts())
            ->spa($this->whiteLabelSpaMode())
            ->databaseNotifications($this->whiteLabelDatabaseNotifications())
            ->databaseNotificationsPolling($this->whiteLabelDatabaseNotificationsPolling())
            ->renderHook('panels::head.start', $this->whiteLabelHeadHook());
    }
}
