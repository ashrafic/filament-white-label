# Filament White-Label

> Total panel rebranding. For you and every tenant. No code.

Logo, colors, fonts, layout, CSS, footer — every tenant gets their own brand. Or rebrand your own portal in a single-tenant setup. Install, add one line to your PanelProvider, done.

---

[![Packagist Version](https://img.shields.io/packagist/v/ashrafic/filament-white-label?style=flat-square&color=blue&logo=packagist)](https://packagist.org/packages/ashrafic/filament-white-label)
[![Docs](https://img.shields.io/badge/docs-filament--white--label-blue?style=flat-square&logo=readthedocs)](https://docs.ashraficlabs.com/filament-white-label)
[![PHP Version](https://img.shields.io/badge/php-%5E8.2-777bb4?style=flat-square&logo=php&logoColor=white)](https://php.net)
[![Filament](https://img.shields.io/badge/filament-%5E5.0-fbbf24?style=flat-square&logo=laravel)](https://filamentphp.com)
[![License](https://img.shields.io/badge/license-MIT-green?style=flat-square&logo=open-source-initiative)](LICENSE.md)

---

## Installation

```bash
composer require ashrafic/filament-white-label
php artisan white-label:install
php artisan migrate
```

Add the trait to your tenant model:

```php
use FilamentWhiteLabel\Traits\HasWhiteLabel;

class Team extends Model
{
    use HasWhiteLabel;
}
```

One line in your PanelProvider:

```php
$panel->whiteLabel();
```

Done. Your tenants see a **White Label** settings page.

---

## Documentation

Full docs at **[docs.ashraficlabs.com/filament-white-label](https://docs.ashraficlabs.com/filament-white-label)**

| | |
|---|---|
| [Getting Started](https://docs.ashraficlabs.com/filament-white-label/getting-started) | Overview, requirements, quick start |
| [Installation](https://docs.ashraficlabs.com/filament-white-label/installation) | Composer, install command, trait setup |
| [Configuration](https://docs.ashraficlabs.com/filament-white-label/configuration) | Full config reference — every option |
| [Features Overview](https://docs.ashraficlabs.com/filament-white-label/features) | All customization surfaces at a glance |
| [Brand Settings](https://docs.ashraficlabs.com/filament-white-label/features/brand-settings) | Name, logos, colors, fonts, CSS theme, custom CSS |
| [Layout Settings](https://docs.ashraficlabs.com/filament-white-label/features/layout-settings) | Navigation, sidebar, breadcrumbs, dimensions, footer |
| [Advanced Settings](https://docs.ashraficlabs.com/filament-white-label/features/advanced-settings) | SPA mode, notifications, density, modals, transitions |
| [Branded Login](https://docs.ashraficlabs.com/filament-white-label/features/branded-login) | Tenant-branded auth page |
| [Integration Patterns](https://docs.ashraficlabs.com/filament-white-label/features/integration-patterns) | Macro, granular trait, manual, conditional |
| [Resolution Flow](https://docs.ashraficlabs.com/filament-white-label/reference/resolution-flow) | Tenant → panel → global → config defaults |
| [Cache & Security](https://docs.ashraficlabs.com/filament-white-label/reference/cache-and-security) | Caching strategy, CSS sanitization, threat mitigations |
| [Events](https://docs.ashraficlabs.com/filament-white-label/reference/events) | WhiteLabelSettingsSaved, WhiteLabelSettingsDeleted |

---

## Screenshots

| Brand Identity | Colors |
|---|---|
| [![Brand Identity](https://docs.ashraficlabs.com/filament-white-label/assets/screenshots/brand-identity.png)](https://docs.ashraficlabs.com/filament-white-label/features/brand-settings) | [![Colors](https://docs.ashraficlabs.com/filament-white-label/assets/screenshots/colors.png)](https://docs.ashraficlabs.com/filament-white-label/features/brand-settings) |

| Typography, Styling & Custom CSS | Layout: Navigation, Sidebar, Display |
|---|---|
| [![Typography & CSS](https://docs.ashraficlabs.com/filament-white-label/assets/screenshots/typography-style-custom-css.png)](https://docs.ashraficlabs.com/filament-white-label/features/brand-settings) | [![Layout](https://docs.ashraficlabs.com/filament-white-label/assets/screenshots/layout-nav-sidebar-display.png)](https://docs.ashraficlabs.com/filament-white-label/features/layout-settings) |

| Dimensions & Footer | Advanced Settings |
|---|---|
| [![Dimensions & Footer](https://docs.ashraficlabs.com/filament-white-label/assets/screenshots/dimension-footer.png)](https://docs.ashraficlabs.com/filament-white-label/features/layout-settings) | [![Advanced](https://docs.ashraficlabs.com/filament-white-label/assets/screenshots/advanced.png)](https://docs.ashraficlabs.com/filament-white-label/features/advanced-settings) |

---

## What You Can Customize

| Category | Highlights |
|---|---|
| Brand identity | Name, logo (light + dark), logo height, favicon |
| Colors | 6 roles — palette presets + custom hex picker |
| Typography | 49 Google Fonts via CDN |
| CSS theme | Border radius, input radius, badge shape, shadow intensity |
| Layout | Top bar, top nav, collapsible sidebar, breadcrumbs |
| Dimensions | Content width, sidebar width, heading size |
| Density | Font scale, form density, table row density, modal size |
| Behavior | SPA mode, unsaved changes alerts, database notifications |
| Custom CSS | Sanitized CSS injection (max 50KB) |
| Login page | Branded auth experience (extends native Filament login) |
| Footer | Custom text + dynamic label/URL links |

---

## Operating Modes

| Mode | When | How |
|---|---|---|
| Multi-tenant | `Filament::getTenant()` resolves | Per-tenant settings record |
| Single-tenant | No tenant, global record exists | One global settings record |
| Config-only | No records exist | `config('filament-white-label.defaults.*')` |

Works **before** and **after** you adopt multi-tenancy.

---

## Requirements

- PHP 8.2+
- Laravel 11+
- Filament v5.x

---

## Testing

```bash
composer test     # Pest — 24 tests, 167 assertions
composer lint     # Pint
```

---

## License

MIT. See [LICENSE.md](LICENSE.md).

---

Full docs at **[docs.ashraficlabs.com/filament-white-label](https://docs.ashraficlabs.com/filament-white-label)**
