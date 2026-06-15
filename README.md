# Filament White-Label

> Give every tenant their own brand. No code. No limits.

Filament White-Label lets each tenant in your multi-tenant app — or you in a single-tenant setup — customize their panel's entire look and feel from a clean UI. Logo, colors, fonts, layout, CSS, footer, and more. All configurable per tenant, per panel, without touching code.

---

[![Packagist Version](https://img.shields.io/packagist/v/ashrafic/filament-white-label?style=flat-square&color=blue)](https://packagist.org/packages/ashrafic/filament-white-label)
[![Packagist Downloads](https://img.shields.io/packagist/dt/ashrafic/filament-white-label?style=flat-square)](https://packagist.org/packages/ashrafic/filament-white-label)
[![PHP Version](https://img.shields.io/badge/php-%5E8.2-777bb4?style=flat-square&logo=php)](https://php.net)
[![Filament](https://img.shields.io/badge/filament-%5E5.0-fbbf24?style=flat-square)](https://filamentphp.com)
[![License](https://img.shields.io/badge/license-MIT-green?style=flat-square)](LICENSE.md)
[![Tests](https://img.shields.io/badge/tests-%E2%9C%93%20passing-success?style=flat-square)]()

---

## Why This Exists

Filament v5 ships with native multi-tenancy. Switching tenants works. Resource scoping works. But every panel looks the same — your brand, your logo, your colors.

**Your customers want THEIR brand on THEIR panel.**

If you run a SaaS, an agency, or any multi-tenant app, you've either manually wired `brandLogo()` + `colors()` + `favicon()` for every tenant (painful, repetitive), or you've accepted that all tenants look identical (bad UX).

**Filament White-Label fixes this.** Install it. Add one line to your PanelProvider. Tenants configure their own branding from a settings page. Done.

---

## What You Can Customize

| Category | Settings |
|---|---|
| **Brand Identity** | Brand name, logo (light + dark), logo height, favicon |
| **Colors** | 6 color roles — primary, secondary, danger, warning, success, info — with palette presets + custom hex |
| **Typography** | 49 Google Fonts via CDN (Inter, Roboto, Poppins, JetBrains Mono...) |
| **CSS Theme** | Border radius, input border radius, badge shape, shadow intensity |
| **Layout** | Top bar, top navigation, collapsible sidebar, fully-collapsible sidebar, nav group toggling, breadcrumbs |
| **Dimensions** | Content width, sidebar width, page heading size, nav item spacing |
| **Density** | Font scale, form density, table row density, modal size, transition speed |
| **Behavior** | SPA mode, unsaved changes alerts, database notifications + polling interval |
| **Custom CSS** | Arbitrary CSS injected into `<head>`, sanitized for XSS |
| **Branded Login** | Logo, brand name, and colors on auth pages (extends native Filament login) |
| **Footer** | Custom text row + repeater of label/URL links at the bottom of every page |

49 fonts, 13 CSS theme controls, 6 layout toggles, 4 advanced toggles, 2 footer fields. All in one metadata JSON column — no schema migrations when you add more later.

---

## Requirements

- PHP 8.2+
- Laravel 11+
- Filament v5.x

---

## Installation

```bash
composer require ashrafic/filament-white-label
php artisan white-label:install
php artisan migrate
```

Add the `HasWhiteLabel` trait to your tenant model (skip if you don't use multi-tenancy):

```php
use FilamentWhiteLabel\Traits\HasWhiteLabel;

class Team extends Model
{
    use HasWhiteLabel;
}
```

That's it. Laravel auto-discovers the service provider.

---

## One-Line Integration

Add `->whiteLabel()` to your PanelProvider. Every feature wired at once:

```php
use Filament\Panel;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('admin')
            ->path('admin')
            ->tenant(Team::class)
            ->whiteLabel() // <-- all branding, auto-wired
            ->resources([
                \FilamentWhiteLabel\Resources\WhiteLabelSettingsResource::class,
            ]);
    }
}
```

Your tenants now see a **White Label** navigation item with sub-pages:

| Page | What They Configure |
|---|---|
| **Brand** | Name, logos (light/dark), favicon, 6 colors, font family, CSS theme (border radius, shadows, badges), custom CSS |
| **Layout** | Navigation layout, sidebar behavior, breadcrumbs, dimensions, footer |
| **Advanced** | SPA mode, unsaved changes alerts, database notifications, font scale, density, modals, transitions |

---

## Settings Pages

### Brand

Configure visual identity. Includes a **palette picker** — select from every Filament color palette (Zinc, Emerald, Amber...) with a live hex preview, or enter any custom hex value.

- Brand Name, Logo (Light), Logo (Dark), Logo Height, Favicon
- Primary / Secondary / Danger / Warning / Success / Info colors
- Font family (49 options, searchable, CDN-loaded)
- Border radius (buttons, cards, inputs), input border radius, shadow intensity, badge shape
- Custom CSS textarea (sanitized, max 50KB)

### Layout

Control the panel chrome:

- Top Bar — show/hide user menu and notifications
- Top Navigation — move nav to top bar (disables sidebar)
- Collapsible Sidebar — collapse to icons only
- Fully Collapsible Sidebar — hide sidebar completely
- Collapsible Navigation Groups — allow groups to expand/collapse
- Breadcrumbs — show breadcrumb navigation
- Content Width, Sidebar Width, Page Heading Size, Nav Item Spacing
- **Footer** — text line + add/remove links (label + URL pairs)

### Advanced

Fine-tune behavior and density:

- Unsaved Changes Alerts — warn before leaving dirty forms
- SPA Mode — faster navigation via single-page rendering
- Database Notifications — in-app notification bell
- Notification Polling Interval — 10s to 5m
- Font Scale — 90% to 120% (accessibility)
- Form Density — compact, default, spacious
- Table Row Density — compact, default, spacious
- Default Modal Size — small (480px) to extra-large (1024px)
- Transition Speed — none, fast, default, slow

---

## Integration Patterns

### Pattern 1: Macro (Recommended)

```php
$panel->whiteLabel();
```

Wires all 16+ settings at once. No individual calls needed.

### Pattern 2: Conditional

```php
$panel->whiteLabel(auth()->user()->can('manage-branding'));
```

Disabled users see panel defaults. Nothing breaks.

### Pattern 3: Granular (Pick & Choose)

Use the `HasWhiteLabel` concern on your PanelProvider:

```php
use FilamentWhiteLabel\Concerns\HasWhiteLabel;

class AdminPanelProvider extends PanelProvider
{
    use HasWhiteLabel;

    public function panel(Panel $panel): Panel
    {
        return $panel
            ->brandName($this->whiteLabelBrandName())
            ->brandLogo($this->whiteLabelLogo())
            ->colors($this->whiteLabelColors())
            ->font($this->whiteLabelFontFamily())
            ->topNavigation($this->whiteLabelTopNavigation())
            ->renderHook('panels::head.end', $this->whiteLabelHeadHook())
            ->renderHook('panels::footer', $this->whiteLabelFooter());
    }
}
```

### Pattern 4: Manual (Zero Traits)

```php
use FilamentWhiteLabel\FilamentWhiteLabel;

$panel
    ->brandName(fn () => FilamentWhiteLabel::brandName())
    ->brandLogo(fn () => FilamentWhiteLabel::logoUrl())
    ->colors(fn () => FilamentWhiteLabel::colors());
```

### Branded Login

```php
$panel->login(\FilamentWhiteLabel\Pages\Auth\BrandedLogin::class);
```

Extends Filament's native `Login` page. Logo, brand name, and colors render automatically. All auth features preserved (password reset, MFA, social login).

---

## Configuration

All defaults live in `config/filament-white-label.php`:

| Key | Default | Description |
|---|---|---|
| `enabled` | `true` | Master kill-switch |
| `cache_ttl` | `300` | Seconds to cache resolved settings. `0` = off |
| `disk` | `public` | Laravel filesystem disk for uploads |
| `storage_path_prefix` | `brand` | Base path for tenant-scoped upload directories |
| `defaults.brand_name` | `APP_NAME` | Fallback brand name |
| `defaults.logo` | `null` | Default logo URL or path |
| `defaults.dark_mode_logo` | `null` | Default dark-mode logo |
| `defaults.favicon` | `null` | Default favicon URL or path |
| `defaults.brand_logo_height` | `null` | Default CSS height for logo |
| `defaults.font_family` | `Inter` | Default font (no CDN hit for Inter) |
| `defaults.colors.*` | `null` | Default color palette (6 roles) |
| `defaults.topbar` | `true` | Show top bar by default |
| `defaults.top_navigation` | `false` | Top nav off by default |
| `defaults.sidebar_collapsible_on_desktop` | `false` | Sidebar icon-only mode off |
| `defaults.sidebar_fully_collapsible_on_desktop` | `false` | Sidebar hide mode off |
| `defaults.collapsible_navigation_groups` | `true` | Nav groups collapsible |
| `defaults.breadcrumbs` | `true` | Breadcrumbs on |
| `defaults.unsaved_changes_alerts` | `false` | Alerts off |
| `defaults.spa_mode` | `false` | SPA off |
| `defaults.database_notifications` | `false` | Notifications off |
| `defaults.database_notifications_polling` | `30s` | Polling interval |
| `defaults.border_radius` | `default` | Button/card/input rounding |
| `defaults.input_border_radius` | `null` | Input-specific override |
| `defaults.badge_shape` | `default` | Badge border radius |
| `defaults.shadow_intensity` | `default` | Card/dropdown shadow |
| `defaults.container_width` | `null` | Content max-width |
| `defaults.sidebar_width` | `null` | Sidebar fixed width |
| `defaults.heading_size` | `default` | Page h1 font size |
| `defaults.nav_item_spacing` | `default` | Sidebar item vertical padding |
| `defaults.font_scale` | `null` | Global font-size multiplier |
| `defaults.form_density` | `default` | Form section spacing |
| `defaults.table_row_density` | `default` | Table row padding |
| `defaults.modal_size` | `default` | Default modal max-width |
| `defaults.transition_speed` | `default` | CSS transition duration |
| `defaults.footer_text` | `null` | Footer text line |
| `defaults.footer_links` | `[]` | Footer link array |
| `security.disable_custom_css` | `false` | Block all custom CSS |
| `security.max_css_length` | `50000` | Max CSS bytes |
| `ui.navigation_group` | `White Label` | Sidebar group label |
| `ui.navigation_sort` | `10` | Sort position |
| `login.enabled` | `true` | Enable branded login |
| `fonts.enabled` | `true` | Enable Google Fonts CDN |
| `fonts.api_key` | `null` | Optional Google Fonts API key |

---

## How It Works

### Resolution Flow

```
Request arrives
  → Panel::whiteLabel() calls closures
    → WhiteLabel::resolve()
      ├─ Filament::getTenant()?
      │   ├─ Yes → cache key: tenant:{type}:{id}:panel:{panelId}
      │   │   ├─ Cache hit → return
      │   │   └─ Cache miss → DB query → Cache::put(ttl) → return
      │   └─ No → cache key: global:panel:{panelId}
      │       ├─ Cache hit → return
      │       └─ Cache miss → DB query (null tenant) → Cache::put(ttl) → return
      └─ Each closure reads metadata → falls back → config defaults
```

### Three Operating Modes

| Mode | When | Resolution |
|---|---|---|
| **Multi-Tenant** | `Filament::getTenant()` returns a tenant | Tenant's `WhiteLabelSettings` record |
| **Single-Tenant** | No tenant, global record exists | Global record (`tenant_type = null`) |
| **Config-Only** | No records at all | `config('filament-white-label.defaults.*')` |

Works **before** you adopt multi-tenancy and continues working as you scale. Add the `HasWhiteLabel` trait to your tenant model later — it auto-creates settings for existing tenants.

### Multi-Panel

The `panel_id` column lets a single tenant have different branding per panel:

- `panel_id = 'admin'` → admin panel gets one look
- `panel_id = 'app'` → customer panel gets another
- `panel_id = null` → fallback for any panel

Resolution priority: specific panel → global (null panel_id).

### Cache

Settings are cached for 300 seconds (configurable). Cache keys include tenant morph class, ID, and panel ID. Invalidated automatically on save/delete via `WhiteLabelSettingsObserver`. Manual clear:

```bash
php artisan white-label:clear-cache
php artisan white-label:clear-cache --tenant=5
```

---

## Storage

Uploaded logos and favicons are stored in tenant-scoped directories:

```
brand/App.Models.Team-5/logos/
brand/App.Models.Team-5/favicons/
brand/global/logos/
```

Configurable disk (`public`, `s3`, `r2`, `digitalocean`) via `config('filament-white-label.disk')`.

---

## Security

| Threat | Mitigation |
|---|---|
| XSS via custom CSS | `<script>` tags stripped (multiline, mixed-case) |
| `javascript:` URLs in CSS | Replaced with `invalid-protocol-removed:` |
| `expression()` calls (IE) | Stripped |
| Malicious file uploads | `image()` validation, mime whitelist, max size, tenant-scoped directories |
| Cross-tenant file access | Storage path includes `tenant_type-tenant_id` |
| Cache poisoning | Keys include morph class + tenant ID + panel ID |
| Excessive CSS | Configurable max length (default 50KB) |
| CSS entirely disabled | `security.disable_custom_css = true` |

---

## FAQ

**Does this work without multi-tenancy?**
Yes. Falls back to global record → config defaults. Perfect for single-tenant apps that want centralized brand management.

**Can I use S3 for file uploads?**
Yes. Set `FILAMENT_WHITE_LABEL_DISK=s3` in your `.env`.

**Can tenants have different branding per panel?**
Yes. The `panel_id` column supports per-panel settings. Admin panel and app panel can look different.

**What happens when a tenant is deleted?**
The `WhiteLabelSettings` record remains (polymorphic morphs don't cascade). Orphaned records are safely ignored.

**Does the branded login work with social auth and MFA?**
Yes. `BrandedLogin` extends Filament's native `Login` page. All auth features preserved.

**Can I mix `->whiteLabel()` with individual calls?**
Don't. The last call wins. Set defaults in config, not in the PanelProvider.

**Is there a dark mode per-tenant?**
Logos have light/dark variants. Colors apply in both modes. Full dark mode theme config is forward-compatible in the metadata JSON.

**How do I disable specific features?**
Set defaults in `config/filament-white-label.php`. Hide form fields via config. Disable the entire plugin with `FILAMENT_WHITE_LABEL_ENABLED=false`.

---

## Testing

```bash
composer test     # Pest
composer lint     # Pint (dry-run)
composer lint:fix # Pint (auto-fix)
```

---

## License

MIT. See [LICENSE.md](LICENSE.md).

---

*Built for Filament v5 and PHP 8.2+.*
