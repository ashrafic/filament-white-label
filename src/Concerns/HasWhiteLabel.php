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

    public function whiteLabel(Panel $panel): Panel
    {
        if (! config('filament-white-label.enabled', true)) {
            return $panel;
        }

        return $panel
            ->brandName($this->whiteLabelBrandName())
            ->brandLogo($this->whiteLabelLogo())
            ->favicon($this->whiteLabelFavicon())
            ->colors($this->whiteLabelColors())
            ->font($this->whiteLabelFontFamily())
            ->renderHook('panels::head.start', $this->whiteLabelHeadHook());
    }
}