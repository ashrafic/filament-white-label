<?php

declare(strict_types=1);

namespace FilamentWhiteLabel\Concerns;

use Closure;
use Filament\Panel;
use FilamentWhiteLabel\Services\WhiteLabel;

trait HasWhiteLabel
{
    public function whiteLabelBrandName(): Closure
    {
        return fn (): ?string => WhiteLabel::brandName();
    }

    public function whiteLabelLogo(): Closure
    {
        return fn (): ?string => WhiteLabel::logoUrl();
    }

    public function whiteLabelBrandLogoHeight(): Closure
    {
        return fn (): ?string => WhiteLabel::brandLogoHeight();
    }

    public function whiteLabelDarkModeBrandLogo(): Closure
    {
        return fn (): ?string => WhiteLabel::darkModeBrandLogoUrl();
    }

    public function whiteLabelFavicon(): Closure
    {
        return fn (): ?string => WhiteLabel::faviconUrl();
    }

    public function whiteLabelColors(): Closure
    {
        return fn (): array => WhiteLabel::colors();
    }

    public function whiteLabelFontFamily(): Closure
    {
        return fn (): string => WhiteLabel::fontFamily();
    }

    public function whiteLabelHeadHook(): Closure
    {
        return fn (): string => WhiteLabel::fontLinkTag().WhiteLabel::customCssTag();
    }

    public function whiteLabelTopbar(): Closure
    {
        return fn (): bool => WhiteLabel::topbar();
    }

    public function whiteLabelTopNavigation(): Closure
    {
        return fn (): bool => WhiteLabel::topNavigation();
    }

    public function whiteLabelSidebarCollapsibleOnDesktop(): Closure
    {
        return fn (): bool => WhiteLabel::sidebarCollapsibleOnDesktop();
    }

    public function whiteLabelSidebarFullyCollapsibleOnDesktop(): Closure
    {
        return fn (): bool => WhiteLabel::sidebarFullyCollapsibleOnDesktop();
    }

    public function whiteLabelCollapsibleNavigationGroups(): Closure
    {
        return fn (): bool => WhiteLabel::collapsibleNavigationGroups();
    }

    public function whiteLabelBreadcrumbs(): Closure
    {
        return fn (): bool => WhiteLabel::breadcrumbs();
    }

    public function whiteLabelUnsavedChangesAlerts(): Closure
    {
        return fn (): bool => WhiteLabel::unsavedChangesAlerts();
    }

    public function whiteLabelSpaMode(): Closure
    {
        return fn (): bool => WhiteLabel::spaMode();
    }

    public function whiteLabelDatabaseNotifications(): Closure
    {
        return fn (): bool => WhiteLabel::databaseNotifications();
    }

    public function whiteLabelDatabaseNotificationsPolling(): Closure
    {
        return fn (): ?string => WhiteLabel::databaseNotificationsPolling();
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
            ->darkModeBrandLogo($this->whiteLabelDarkModeBrandLogo())
            ->favicon($this->whiteLabelFavicon())
            ->colors($this->whiteLabelColors())
            ->font($this->whiteLabelFontFamily())
            ->topbar($this->whiteLabelTopbar())
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
