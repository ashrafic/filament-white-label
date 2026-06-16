# Filament White-Label

> Give every tenant their own brand. No code.

A Composer plugin that lets each tenant in your multi-tenant Filament app — or you in a single-tenant setup — customize their panel's entire look from a clean UI. Logo, colors, fonts, layout, CSS, footer, and more.

---

[![Packagist Version](https://img.shields.io/packagist/v/ashrafic/filament-white-label?style=flat-square&color=blue)](https://packagist.org/packages/ashrafic/filament-white-label)
[![Packagist Downloads](https://img.shields.io/packagist/dt/ashrafic/filament-white-label?style=flat-square)](https://packagist.org/packages/ashrafic/filament-white-label)
[![PHP Version](https://img.shields.io/badge/php-%5E8.2-777bb4?style=flat-square&logo=php)](https://php.net)
[![Filament](https://img.shields.io/badge/filament-%5E5.0-fbbf24?style=flat-square)](https://filamentphp.com)
[![License](https://img.shields.io/badge/license-MIT-green?style=flat-square)](LICENSE.md)

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

Full documentation at **[docs.ashraficlabs.com/filament-white-label](https://docs.ashraficlabs.com/filament-white-label)**

| | |
|---|---|
| [Installation](https://docs.ashraficlabs.com/filament-white-label/installation) | Requirements, setup, trait, first launch |
| [Integration](https://docs.ashraficlabs.com/filament-white-label/integration) | Macro, granular trait, manual, conditional, login page |
| [Brand Settings](https://docs.ashraficlabs.com/filament-white-label/brand) | Name, logos, favicon, colors, fonts, CSS theme, custom CSS |
| [Layout Settings](https://docs.ashraficlabs.com/filament-white-label/layout) | Navigation, sidebar, breadcrumbs, dimensions, footer |
| [Advanced Settings](https://docs.ashraficlabs.com/filament-white-label/advanced) | SPA mode, notifications, density, modals, transitions |
| [Configuration](https://docs.ashraficlabs.com/filament-white-label/configuration) | Full config reference — every option with defaults |
| [How It Works](https://docs.ashraficlabs.com/filament-white-label/how-it-works) | Resolution flow, cache, multi-panel, operating modes |
| [Security](https://docs.ashraficlabs.com/filament-white-label/security) | CSS sanitization, file upload scoping, threat mitigations |

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
