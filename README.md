# Filament White-Label

> **The only white-label branding plugin for Filament.** Let every tenant rebrand their admin panel in minutes — no code required.

[![Latest Version](https://img.shields.io/packagist/v/filament-white-label/filament-white-label.svg?style=flat-square)](https://packagist.org/packages/filament-white-label/filament-white-label)
[![License](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)](LICENSE.md)

Filament White-Label is a **Composer plugin** that adds per-tenant (or per-panel) branding to Filament v5 panels. Every tenant can customize their own logo, colors, fonts, layout, and even the login page — without touching a single line of code.

---

## Table of Contents

- [Features](#features)
- [Requirements](#requirements)
- [Installation](#installation)
- [Quick Start](#quick-start)
- [Settings Pages](#settings-pages)
  - [Brand](#brand)
  - [Layout](#layout)
  - [Advanced](#advanced)
- [Configuration](#configuration)
- [Integration Patterns](#integration-patterns)
  - [One-Line Integration (Macro)](#one-line-integration-macro)
  - [Granular Integration (Trait)](#granular-integration-trait)
  - [Manual Integration (No Traits)](#manual-integration-no-traits)
- [Branded Login Page](#branded-login-page)
- [Email Branding](#email-branding)
- [How It Works](#how-it-works)
  - [Resolution Flow](#resolution-flow)
  - [Three Operating Modes](#three-operating-modes)
  - [Multi-Panel Support](#multi-panel-support)
  - [Cache Invalidation](#cache-invalidation)
- [Security](#security)
- [FAQ](#faq)
- [Testing](#testing)
- [License](#license)

---

## Features

- **Brand Identity** — Brand name, logo (light & dark), logo height, favicon
- **Color System** — 6 color pickers (primary, secondary, danger, warning, success, info)
- **Typography** — 50+ Google Fonts via CDN
- **Layout Control** — Top navigation, sidebar collapsible, breadcrumbs, and more
- **Advanced Settings** — SPA mode, unsaved changes alerts, database notifications
- **Custom CSS** — Sanitized CSS injection per tenant
- **Email Branding** — Per-tenant from address and name
- **Branded Login Page** — Tenant-specific login experience
- **Multi-Panel** — Different branding per panel for the same tenant
- **Cache-First** — 5-minute cache with automatic invalidation
- **Zero Breaking Changes** — Completely opt-in, all features are graceful fallbacks

---

## Requirements

- **PHP** 8.2+
- **Laravel** 11+
- **Filament** v5

---

## Installation

Install via Composer:

```bash
composer require filament-white-label/filament-white-label
```

Run the install command:

```bash
php artisan white-label:install
```

This publishes the config file and migration. Review them, then run the migration:

```bash
php artisan migrate
```

Add the `HasWhiteLabel` trait to your **Tenant model** (e.g., `Team`, `Account`, `Organization`):

```php
use FilamentWhiteLabel\Traits\HasWhiteLabel;

class Team extends Model
{
    use HasWhiteLabel;
}
```

That's it. The package auto-discovers the service provider.

---

## Quick Start

### One-Line Integration

The fastest way to wire everything up. Add `->whiteLabel()` to your PanelProvider:

```php
use Filament\Panel;
use Filament\PanelProvider;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->tenant(Team::class)
            ->whiteLabel() // ← One line. All branding auto-wired.
            ->resources([
                \FilamentWhiteLabel\Resources\WhiteLabelSettingsResource::class,
            ]);
    }
}
```

> **Important:** `->whiteLabel()` takes ownership of all panel styling methods (`brandName`, `brandLogo`, `colors`, `topNavigation`, `sidebarCollapsibleOnDesktop`, etc.). Do not mix `->whiteLabel()` with individual styling calls on the same panel — the last call wins. Set defaults in `config/filament-white-label.php` instead.

### What You Get

A **"White Label"** sidebar item with three sub-pages:

| Page | What You Can Configure |
|------|------------------------|
| **Brand** | Brand name, logo (light/dark), logo height, favicon, colors, font, custom CSS, email |
| **Layout** | Top bar, top navigation, sidebar collapsible/fully-collapsible, collapsible nav groups, breadcrumbs |
| **Advanced** | Unsaved changes alerts, SPA mode, database notifications, notification polling |

---

## Settings Pages

### Brand

Configure your tenant's visual identity:

- **Brand Name** — Appears in the browser title and fallback logo alt text
- **Logo (Light)** — PNG, JPG, SVG, WebP. Recommended aspect ratio 3:1
- **Logo (Dark)** — Optional dark-mode variant. Falls back to light logo
- **Logo Height** — CSS height value (e.g., `2.5rem`, `40px`). Leave empty for Filament default
- **Favicon** — Square icon, PNG/ICO/SVG. Max 512KB
- **Colors** — 6 color pickers: Primary, Secondary, Danger, Warning, Success, Info
- **Font Family** — 50+ Google Fonts via CDN. Inter is the default (no CDN request)
- **Custom CSS** — Injected into `<head>` after sanitization. Max 50KB
- **Email Branding** — From address and name for outgoing emails

### Layout

Control the panel chrome:

- **Top Bar** — Show/hide the top bar with user menu and notifications
- **Top Navigation** — Move navigation from sidebar to top bar
- **Collapsible Sidebar** — Allows sidebar to collapse to icons only
- **Fully Collapsible Sidebar** — Allows sidebar to hide completely
- **Collapsible Navigation Groups** — Allow navigation groups to expand/collapse
- **Breadcrumbs** — Show breadcrumb navigation

### Advanced

Fine-tune behavior:

- **Unsaved Changes Alerts** — Warn before leaving pages with unsaved changes
- **SPA Mode** — Single-page application mode for faster navigation
- **Database Notifications** — Enable in-app notifications
- **Polling Interval** — How often to check for new notifications

---

## Configuration

All options live in `config/filament-white-label.php`:

| Key | Default | Description |
|-----|---------|-------------|
| `enabled` | `true` | Master switch. Set to `false` to disable all features globally |
| `cache_ttl` | `300` | Cache duration in seconds. `0` disables caching |
| `disk` | `public` | Laravel filesystem disk for logo/favicon uploads |
| `storage_path_prefix` | `brand` | Base path for tenant-scoped uploads |
| `defaults.brand_name` | `APP_NAME` | Fallback brand name |
| `defaults.logo` | `null` | URL or path to default logo |
| `defaults.dark_mode_logo` | `null` | URL or path to default dark-mode logo |
| `defaults.favicon` | `null` | URL or path to default favicon |
| `defaults.brand_logo_height` | `null` | Default logo height CSS |
| `defaults.font_family` | `Inter` | Fallback font family |
| `defaults.colors` | Blue/Slate | Fallback color palette (6 colors) |
| `defaults.email_from_address` | `MAIL_FROM_ADDRESS` | Fallback email from address |
| `defaults.email_from_name` | `MAIL_FROM_NAME` | Fallback email from name |
| `defaults.topbar` | `true` | Default top bar visibility |
| `defaults.top_navigation` | `false` | Default top navigation |
| `defaults.sidebar_collapsible_on_desktop` | `false` | Default sidebar collapsible |
| `defaults.sidebar_fully_collapsible_on_desktop` | `false` | Default fully collapsible |
| `defaults.collapsible_navigation_groups` | `true` | Default collapsible groups |
| `defaults.breadcrumbs` | `true` | Default breadcrumbs |
| `defaults.unsaved_changes_alerts` | `false` | Default unsaved changes alerts |
| `defaults.spa_mode` | `false` | Default SPA mode |
| `defaults.database_notifications` | `false` | Default database notifications |
| `defaults.database_notifications_polling` | `30s` | Default polling interval |
| `security.disable_custom_css` | `false` | Block all custom CSS injection |
| `security.max_css_length` | `50000` | Max CSS length in bytes |
| `ui.show_preview` | `false` | Show live brand preview (future feature) |
| `ui.navigation_group` | `White Label` | Sidebar group label |
| `ui.navigation_sort` | `10` | Sort order within the group |
| `email.enabled` | `true` | Enable per-tenant email branding |
| `login.enabled` | `true` | Enable branded login page |
| `fonts.enabled` | `true` | Enable Google Fonts CDN |
| `fonts.api_key` | `null` | Optional Google Fonts API key |

---

## Integration Patterns

### One-Line Integration (Macro)

The `->whiteLabel()` macro is a `Panel` macro registered by the service provider. It wires **all** branding features at once:

```php
public function panel(Panel $panel): Panel
{
    return $panel
        ->id('admin')
        ->tenant(Team::class)
        ->whiteLabel() // ← All features
        ->whiteLabel(false) // ← Condition: disable if some logic
        ->whiteLabel(auth()->user()->can('white-label')) // ← Conditional
        ->resources([
            \FilamentWhiteLabel\Resources\WhiteLabelSettingsResource::class,
        ]);
}
```

### Granular Integration (Trait)

Pick and choose which features to apply. Add the `HasWhiteLabel` concern to your PanelProvider:

```php
use FilamentWhiteLabel\Concerns\HasWhiteLabel;

class AdminPanelProvider extends PanelProvider
{
    use HasWhiteLabel;

    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('admin')
            ->tenant(Team::class)
            ->brandName($this->whiteLabelBrandName())
            ->brandLogo($this->whiteLabelLogo())
            ->colors($this->whiteLabelColors())
            ->font($this->whiteLabelFontFamily())
            ->topNavigation($this->whiteLabelTopNavigation())
            ->renderHook('panels::head.start', $this->whiteLabelHeadHook());
    }
}
```

Available closure methods from the `HasWhiteLabel` concern:

| Method | Filament Method | What It Controls |
|--------|----------------|------------------|
| `whiteLabelBrandName()` | `brandName()` | Brand name string |
| `whiteLabelLogo()` | `brandLogo()` | Logo URL (light) |
| `whiteLabelDarkModeBrandLogo()` | `darkModeBrandLogo()` | Logo URL (dark) |
| `whiteLabelBrandLogoHeight()` | `brandLogoHeight()` | Logo height CSS |
| `whiteLabelFavicon()` | `favicon()` | Favicon URL |
| `whiteLabelColors()` | `colors()` | 6-color array |
| `whiteLabelFontFamily()` | `font()` | Font family name |
| `whiteLabelTopbar()` | `topbar()` | Top bar visibility |
| `whiteLabelTopNavigation()` | `topNavigation()` | Top vs sidebar nav |
| `whiteLabelSidebarCollapsibleOnDesktop()` | `sidebarCollapsibleOnDesktop()` | Sidebar icon-only mode |
| `whiteLabelSidebarFullyCollapsibleOnDesktop()` | `sidebarFullyCollapsibleOnDesktop()` | Sidebar hidden mode |
| `whiteLabelCollapsibleNavigationGroups()` | `collapsibleNavigationGroups()` | Group expand/collapse |
| `whiteLabelBreadcrumbs()` | `breadcrumbs()` | Breadcrumb navigation |
| `whiteLabelUnsavedChangesAlerts()` | `unsavedChangesAlerts()` | Leave-page warnings |
| `whiteLabelSpaMode()` | `spa()` | SPA mode |
| `whiteLabelDatabaseNotifications()` | `databaseNotifications()` | In-app notifications |
| `whiteLabelDatabaseNotificationsPolling()` | `databaseNotificationsPolling()` | Polling interval |
| `whiteLabelHeadHook()` | `renderHook('panels::head.start')` | Font CSS + Custom CSS |

### Manual Integration (No Traits)

If you prefer zero trait dependencies, use the static helper directly:

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

Or use the underlying `WhiteLabel` service:

```php
use FilamentWhiteLabel\Services\WhiteLabel;

public function panel(Panel $panel): Panel
{
    return $panel
        ->brandName(fn () => WhiteLabel::brandName())
        ->brandLogo(fn () => WhiteLabel::logoUrl());
}
```

---

## Branded Login Page

Give tenants a branded login experience:

```php
public function panel(Panel $panel): Panel
{
    return $panel
        ->id('admin')
        ->tenant(Team::class)
        ->whiteLabel()
        ->login(\FilamentWhiteLabel\Pages\Auth\BrandedLogin::class);
}
```

The `BrandedLogin` page extends Filament's native `Login` page and injects `brandName`, `brandLogo`, and `brandColors` into the view data. All Filament auth features (password reset, email verification) are preserved.

---

## Email Branding

Automatically apply per-tenant `From` address and name to all outgoing emails.

The listener is **auto-registered** by the service provider (enabled by default). If you prefer manual registration:

```php
// app/Providers/EventServiceProvider.php
protected $listen = [
    \Illuminate\Mail\Events\MessageSending::class => [
        \FilamentWhiteLabel\Listeners\ApplyTenantEmailBranding::class,
    ],
];
```

Disable via config:

```php
'email' => [
    'enabled' => false,
],
```

---

## How It Works

### Resolution Flow

```
HTTP Request → PanelProvider::panel()
    → whiteLabel() macro or closures fire
        → WhiteLabel::resolve()
            ├── enabled === false? → return null (fallback to config)
            ├── Filament::getTenant() → active tenant?
            │     ├── Yes → cacheKey(tenant, panelId) → Cache::get()
            │     │     ├── Hit → return cached WhiteLabelSettings
            │     │     └── Miss → DB query + Cache::put() → return
            │     └── No → resolveGlobal(panelId)
            │           ├── Cache::get() → Hit → return
            │           └── Miss → DB query (whereNull tenant) + Cache::put()
            └── Return WhiteLabelSettings or null
    → Each getter evaluates metadata
        → brandName() = settings.metadata['brand_name'] ?? config default
        → logoUrl() = Storage::url(settings.metadata['logo_path']) ?? config default
        → colors() = array_merge(config defaults, settings.metadata['colors'])
        → fontFamily() = settings.metadata['font_family'] ?? 'Inter'
        → customCssTag() = <style>{sanitized_css}</style>
        → fontLinkTag() = <link> to Google Fonts CDN
    → Filament renders panel with tenant branding
```

### Three Operating Modes

The package works transparently in three scenarios:

| Mode | Condition | Resolution |
|------|-----------|------------|
| **Multi-tenant** | `Filament::getTenant()` returns a tenant | Tenant's `WhiteLabelSettings` record |
| **Single-tenant** | No tenant, but global record exists | Global record (`tenant_type = null, tenant_id = null`) |
| **Config-only** | No records exist at all | `config('filament-white-label.defaults')` |

This means the plugin works **before** you adopt multi-tenancy, and continues working as you scale.

### Multi-Panel Support

The `panel_id` column allows different branding per panel. A tenant can have:

- Different logos on the **admin** panel vs the **app** panel
- Different colors on the **customer** panel vs the **staff** panel

Resolution priority: `panel_id = 'admin'` → `panel_id = null` (fallback). If no panel-specific record exists, the global one is used.

### Cache Invalidation

- **Automatic:** `WhiteLabelSettingsObserver` clears cache on `saved` and `deleted`
- **Manual:** `php artisan white-label:clear-cache`
- **Per-tenant:** `php artisan white-label:clear-cache --tenant=5`

Cache key format: `filament-white-label:tenant:{MorphClass}:{id}:panel:{panelId}`

---

## Security

Custom CSS is sanitized automatically before storage:

| Threat | Mitigation |
|--------|------------|
| `<script>` tags | Stripped (including multiline) |
| `javascript:` URLs in `url()` | Replaced with `invalid-protocol-removed:` |
| `expression()` (IE) | Replaced with `(removed-expression` |
| File upload attacks | `image()` validation, mime type whitelist, max size, tenant-scoped directories |
| Cross-tenant file access | Storage path includes `tenant_type-tenant_id` segment |
| Cache poisoning | Cache keys include tenant morph class + ID + panel ID |
| Excessive CSS | `max_css_length` config limit (default 50KB) |
| Disable CSS entirely | `security.disable_custom_css` config option |

---

## FAQ

**Does this work without multi-tenancy?**
> Yes. Falls back to a global `WhiteLabelSettings` record, then config defaults. Perfect for single-tenant apps that just want centralized brand management.

**Can I set different defaults for one boolean setting?**
> Yes. Set it in `config/filament-white-label.php` under `defaults`. Tenants override it, tenants without configuration get your default.

**How do I clear the cache?**
> `php artisan white-label:clear-cache` or save any settings record (auto-clears).

**Can I use S3 for logos?**
> Yes. Set `disk` in config to any Laravel filesystem disk (e.g., `s3`, `r2`, `digitalocean`).

**Is custom CSS safe?**
> Yes. Script tags and `javascript:` URLs are stripped before storage. Can be fully disabled via `security.disable_custom_css`.

**Can I mix `->whiteLabel()` with individual `->brandName()` calls?**
> Don't. The last call wins. `->whiteLabel()` replaces all styling methods. Set defaults in config, not in the PanelProvider.

**Can one tenant have different branding per panel?**
> Yes. The `panel_id` column supports this. Create a panel-specific record and it takes priority over the global one.

**What happens when a tenant is deleted?**
> The `WhiteLabelSettings` record is not automatically deleted (polymorphic morphs don't support `cascadeOnDelete` natively). Orphaned records are safely ignored.

**Does the login page work with social auth?**
> Yes. `BrandedLogin` extends Filament's native `Login` page. All auth features are preserved.

**Does this work with Filament's dark mode?**
> Yes. Logos support light/dark variants. Colors are applied in both modes. Dark mode per tenant is reserved for a future release via the `metadata` JSON column.

---

## Testing

```bash
composer test
# or
./vendor/bin/pest
```

Format code:

```bash
composer lint
# or
./vendor/bin/pint
```

---

## License

MIT. See [LICENSE.md](LICENSE.md).

---

*Built for Filament v5 and PHP 8.2+.*
