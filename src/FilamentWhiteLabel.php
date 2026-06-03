<?php

declare(strict_types=1);

namespace FilamentWhiteLabel;

use FilamentWhiteLabel\Services\BrandResolver;

class FilamentWhiteLabel
{
    public static function brandName(): ?string
    {
        return BrandResolver::brandName();
    }

    public static function logoUrl(): ?string
    {
        return BrandResolver::logoUrl();
    }

    public static function faviconUrl(): ?string
    {
        return BrandResolver::faviconUrl();
    }

    public static function colors(): array
    {
        return BrandResolver::colors();
    }

    public static function fontFamily(): string
    {
        return BrandResolver::fontFamily();
    }

    public static function customCss(): ?string
    {
        return BrandResolver::customCss();
    }

    public static function customCssTag(): string
    {
        return BrandResolver::customCssTag();
    }

    public static function fontLinkTag(): string
    {
        return BrandResolver::fontLinkTag();
    }

    public static function clearCache(): void
    {
        BrandResolver::clearCache();
    }

    public static function toArray(): array
    {
        return [
            'brand_name' => static::brandName(),
            'logo_url' => static::logoUrl(),
            'favicon_url' => static::faviconUrl(),
            'colors' => static::colors(),
            'font_family' => static::fontFamily(),
        ];
    }
}