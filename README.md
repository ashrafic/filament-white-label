# Filament White-Label

White-label branding for Filament panels. Let every tenant rebrand their admin panel in minutes — no code required.

## Requirements

- PHP 8.2+

- Filament v5

## Installation

1. Install the package via Composer:
   ```bash
   composer require filament-white-label/filament-white-label
   ```

2. Run the install command:
   ```bash
   php artisan white-label:install
   ```

   This publishes the config and migration files. Review them, then:

3. Run the migration:
   ```bash
   php artisan migrate
   ```

4. Add the `HasBrandSettings` trait to your Tenant model:
   ```php
   use FilamentWhiteLabel\Traits\HasBrandSettings;

   class Team extends Model
   {
       use HasBrandSettings;
   }
   ```

## Quick Start

Just chain `->whiteLabel()` on your panel:

```php
class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('admin')
            ->whiteLabel()
            ->resources([
                \FilamentWhiteLabel\Resources\BrandSettingsResource::class,
            ]);
    }
}
```

You can also pass a condition: `->whiteLabel(app()->environment('production'))`.

That's it. No traits, no closures. Every tenant sees their own logo, colors, font, favicon, and CSS through the Brand Settings resource.

### Branded Login Page (Optional)

```php
->login(\FilamentWhiteLabel\Pages\Auth\BrandedLogin::class)
```

### Email Branding (Optional)

Register the listener in your `EventServiceProvider`:

```php
protected $listen = [
    \Illuminate\Mail\Events\MessageSending::class => [
        \FilamentWhiteLabel\Listeners\ApplyTenantEmailBranding::class,
    ],
];
```

## Configuration

All configuration is in `config/filament-white-label.php`. Key options:

| Key | Default | Description |
|-----|---------|-------------|
| `enabled` | `true` | Master switch to disable all white-label features |
| `cache_ttl` | `300` | Cache duration in seconds (0 = disabled) |
| `disk` | `public` | Filesystem disk for logo/favicon storage |
| `defaults.brand_name` | `APP_NAME` | Fallback brand name |
| `defaults.colors` | Blue/Slate palette | Fallback colors when no brand is configured |
| `defaults.font_family` | `Inter` | Fallback font family |
| `security.disable_custom_css` | `false` | Set `true` to block all custom CSS |
| `security.max_css_length` | `50000` | Max custom CSS length in bytes |
| `email.enabled` | `true` | Enable per-tenant email branding |
| `fonts.enabled` | `true` | Enable Google Fonts integration |

## Granular Integration

Apply individual features:

```php
use FilamentWhiteLabel\FilamentWhiteLabel;

public function panel(Panel $panel): Panel
{
    return $panel
        ->id('admin')
        ->brandName(fn () => FilamentWhiteLabel::brandName())
        ->brandLogo(fn () => FilamentWhiteLabel::logoUrl())
        ->colors(fn () => FilamentWhiteLabel::colors());
}
```

For featured-flag control with panel chaining, use the `$panel->whiteLabel()` with conditions:

## Security

Custom CSS is sanitized automatically:
- `<script>` tags are stripped
- `javascript:` URLs in `url()` are removed
- `expression()` calls (IE-specific) are stripped

Set `security.disable_custom_css` to `true` in high-security environments (HIPAA, PCI) to block all custom CSS injection.

## How It Works

1. **Brand Settings Model** — One `BrandSettings` record per tenant (polymorphic)
2. **BrandResolver** — Cache-first resolution: cache → database → config defaults
3. **HasWhiteLabel Concern** — Closure-returning methods that evaluate lazily at request time
4. **HasBrandSettings Trait** — Auto-creates default BrandSettings when a tenant is created
5. **Cache Invalidation** — Observer clears cache on save/delete; `php artisan white-label:clear-cache` command available

### Three Operating Modes

| Mode | When | Resolution |
|------|------|-----------|
| Multi-tenant | Tenant is active via `Filament::getTenant()` | Tenant's BrandSettings |
| Single-tenant | No tenant, one global BrandSettings | Global record (`tenant_id = null`) |
| Config-only | No BrandSettings records exist | `config/filament-white-label.defaults` |

## FAQ

**Does this work without multi-tenancy?**
Yes. If no tenant is active, it falls back to a global `BrandSettings` record, or config defaults.

**Can I disable specific features?**
Yes. Use the granular integration method to pick only the features you want, or set `enabled: false` in config.

**How do I clear the cache?**
Run `php artisan white-label:clear-cache` or save any BrandSettings record (cache auto-clears).

**Can I use a custom storage disk (S3)?**
Yes. Set `disk` in config to any Laravel filesystem disk configured in `config/filesystems.php`.

**Is custom CSS safe?**
Yes. `<script>` tags and `javascript:` URLs are stripped. You can also disable custom CSS entirely with `security.disable_custom_css`.

## Testing

```bash
composer test
```

Or run Pest directly:

```bash
./vendor/bin/pest
```

## License

The MIT License (MIT). See [LICENSE.md](LICENSE.md) for more information.