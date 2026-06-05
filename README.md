# Filament White-Label

White-label branding for Filament panels. Let every tenant rebrand their admin panel in minutes — no code required.

## Requirements

- PHP 8.2+
- Filament v5

## Installation

1. Install via Composer:
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

4. Add the `HasWhiteLabel` trait to your Tenant model:
   ```php
   use FilamentWhiteLabel\Traits\HasWhiteLabel;

   class Team extends Model
   {
       use HasWhiteLabel;
   }
   ```

## Quick Start

One line wires everything:

```php
class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('admin')
            ->whiteLabel()
            ->resources([
                \FilamentWhiteLabel\Resources\WhiteLabelSettingsResource::class,
            ]);
    }
}
```

That's it. Tenants get a "White Label" sidebar menu with three sub-pages: **Brand**, **Layout**, **Advanced**.

> **Important:** `->whiteLabel()` takes ownership of all panel styling methods (`brandName`, `brandLogo`, `colors`, `topNavigation`, `sidebarCollapsibleOnDesktop`, etc.). Do not mix `->whiteLabel()` with individual styling calls on the same panel — the last call wins. Set defaults in `config/filament-white-label.php` instead.

### Branded Login Page (Optional)

```php
->login(\FilamentWhiteLabel\Pages\Auth\BrandedLogin::class)
```

### Email Branding (Optional)

In `EventServiceProvider`:

```php
protected $listen = [
    \Illuminate\Mail\Events\MessageSending::class => [
        \FilamentWhiteLabel\Listeners\ApplyTenantEmailBranding::class,
    ],
];
```

## Settings Pages

The "White Label" sidebar item contains three sub-pages:

| Page | Settings |
|------|----------|
| **Brand** | Brand name, logo (light/dark), logo height, favicon, colors (6), font family, custom CSS, email |
| **Layout** | Top navigation, sidebar collapsible/fully-collapsible, collapsible nav groups, breadcrumbs |
| **Advanced** | Unsaved changes alerts, SPA mode, database notifications, notification polling |

## Configuration

All in `config/filament-white-label.php`:

| Key | Default | Description |
|-----|---------|-------------|
| `enabled` | `true` | Master switch |
| `cache_ttl` | `300` | Cache duration (seconds, 0 = disabled) |
| `disk` | `public` | Filesystem disk for uploads |
| `defaults.brand_name` | `APP_NAME` | Fallback brand name |
| `defaults.colors` | Blue/Slate | Fallback color palette |
| `defaults.font_family` | `Inter` | Fallback font |
| `defaults.top_navigation` | `false` | Default for new/unconfigured tenants |
| `defaults.sidebar_collapsible_on_desktop` | `false` | Default for new/unconfigured tenants |
| `defaults.breadcrumbs` | `true` | Default for new/unconfigured tenants |
| `defaults.spa_mode` | `false` | Default for new/unconfigured tenants |
| `security.disable_custom_css` | `false` | Block all custom CSS |
| `security.max_css_length` | `50000` | Max CSS length in bytes |
| `email.enabled` | `true` | Per-tenant email branding |
| `fonts.enabled` | `true` | Google Fonts integration |

## Granular Integration

Apply individual features instead of `->whiteLabel()`:

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

Or use the `HasWhiteLabel` trait for closure methods:

```php
use FilamentWhiteLabel\Concerns\HasWhiteLabel;

class AdminPanelProvider extends PanelProvider
{
    use HasWhiteLabel;

    public function panel(Panel $panel): Panel
    {
        return $panel
            ->brandName($this->whiteLabelBrandName())
            ->topNavigation($this->whiteLabelTopNavigation());
    }
}
```

## Security

Custom CSS is sanitized automatically:
- `<script>` tags are stripped
- `javascript:` URLs in `url()` are removed
- `expression()` calls (IE-specific) are stripped

Set `security.disable_custom_css` to `true` in high-security environments (HIPAA, PCI).

## How It Works

1. **WhiteLabel Service** — Cache-first resolution per tenant + panel: cache → database → config defaults
2. **HasWhiteLabel Concern** — Closure-returning methods that evaluate lazily at request time
3. **HasWhiteLabel Trait** — Auto-creates default settings when a tenant is created
4. **Cache Invalidation** — Observer clears cache on save/delete; `php artisan white-label:clear-cache` command available

### Three Operating Modes

| Mode | When | Resolution |
|------|------|-----------|
| Multi-tenant | `Filament::getTenant()` returns a tenant | Tenant's `WhiteLabelSettings` |
| Single-tenant | No tenant, global record | Global record (`tenant_id = null`) |
| Config-only | No records exist | `config/filament-white-label.defaults` |

### Multi-Panel Support

The `panel_id` column differentiates settings per panel. A tenant can have different branding on the admin panel vs the app panel. If no panel-specific record exists, falls back to `panel_id = null`.

## FAQ

**Does this work without multi-tenancy?**
Yes. Falls back to a global record, then config defaults.

**Can I set a different default for one boolean setting?**
Yes. Set it in `config/filament-white-label.php` under `defaults`. Tenants override it, tenants without configuration get your default.

**How do I clear the cache?**
`php artisan white-label:clear-cache` or save any settings record (auto-clears).

**Can I use S3 for logos?**
Yes. Set `disk` in config to any Laravel filesystem disk.

**Is custom CSS safe?**
Yes. Script tags and `javascript:` URLs are stripped. Can be fully disabled.

**Can I mix `->whiteLabel()` with individual `->brandName()` calls?**
Don't. The last call wins. `->whiteLabel()` replaces all styling methods. Set defaults in config, not in the PanelProvider.

## Testing

```bash
composer test
# or
./vendor/bin/pest
```

## License

MIT. See [LICENSE.md](LICENSE.md).
