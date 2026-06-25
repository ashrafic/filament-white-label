# Translation Support — Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Add full Laravel i18n support — replace all ~230 hardcoded strings with `__()` calls backed by a publishable `lang/en/filament-white-label.php` file.

**Architecture:** Single translation file with flat dot-notation keys. ServiceProvider registers translation namespace via `loadTranslationsFrom()` and publishes via `publishes()`. All user-facing strings in resource pages, commands, and FontService switch to `__()` helper.

**Tech Stack:** PHP 8.2+, Laravel, Filament v5

---

### Task 1: Create English translation file

**Files:**
- Create: `lang/en/filament-white-label.php`

- [ ] **Step 1: Write the translation file**

```php
<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Filament White-Label — English Translations
    |--------------------------------------------------------------------------
    |
    | This file contains all user-facing strings for the White Label plugin.
    | Copy this file to create translations for other languages.
    |
    | Usage: __('filament-white-label::filament-white-label.resource.sections.brand_identity')
    |
    */

    // ─── Navigation & Resource Labels ─────
    'resource.navigation.label'   => 'White Label',
    'resource.label.singular'     => 'White Label Settings',
    'resource.label.plural'       => 'White Label Settings',
    'resource.sub_navigation.brand'    => 'Brand',
    'resource.sub_navigation.layout'   => 'Layout',
    'resource.sub_navigation.advanced' => 'Advanced',

    // ─── Page Titles ─────
    'resource.page.brand.title'       => 'Brand',
    'resource.page.brand.nav_label'   => 'Brand',
    'resource.page.layout.title'      => 'Layout',
    'resource.page.layout.nav_label'  => 'Layout',
    'resource.page.advanced.title'    => 'Advanced',
    'resource.page.advanced.nav_label' => 'Advanced',

    // ─── Sections ─────
    'resource.sections.brand_identity' => 'Brand Identity',
    'resource.sections.colors'         => 'Colors',
    'resource.sections.typography'     => 'Typography',
    'resource.sections.styling'        => 'Styling',
    'resource.sections.custom_css'     => 'Custom CSS',
    'resource.sections.navigation'     => 'Navigation',
    'resource.sections.sidebar'        => 'Sidebar',
    'resource.sections.display'        => 'Display',
    'resource.sections.dimensions'     => 'Dimensions',
    'resource.sections.footer'         => 'Footer',
    'resource.sections.behavior'       => 'Behavior',
    'resource.sections.notifications'  => 'Notifications',

    // ─── Brand Fields ─────
    'resource.fields.brand_name.label'       => 'Brand Name',
    'resource.fields.logo_height.label'      => 'Logo Height',
    'resource.fields.logo_height.placeholder' => '2.5rem',
    'resource.fields.logo_height.helper_text' => 'CSS height value. Leave empty for Filament default.',
    'resource.fields.logo_light.label'       => 'Logo (Light)',
    'resource.fields.logo_dark.label'        => 'Logo (Dark)',
    'resource.fields.logo_dark.helper_text'  => 'Falls back to light logo if not set.',
    'resource.fields.favicon.label'          => 'Favicon',

    // ─── Color Fields ─────
    'resource.fields.colors.primary.label'    => 'Primary',
    'resource.fields.colors.secondary.label'  => 'Secondary',
    'resource.fields.colors.danger.label'     => 'Danger',
    'resource.fields.colors.warning.label'    => 'Warning',
    'resource.fields.colors.success.label'    => 'Success',
    'resource.fields.colors.info.label'       => 'Info',
    'resource.fields.colors.palette.label'    => 'Palette',
    'resource.fields.colors.hex.label'        => 'Hex',
    'resource.fields.colors.custom_hex'       => 'Custom hex...',

    // ─── Typography Fields ─────
    'resource.fields.font_family.label' => 'Font Family',

    // ─── Styling Fields ─────
    'resource.fields.border_radius.label'       => 'Border Radius',
    'resource.fields.border_radius.helper_text' => 'Rounded corners on buttons, cards, inputs, modals, and dropdowns.',
    'resource.fields.input_border_radius.label' => 'Input Border Radius',
    'resource.fields.input_border_radius.helper_text' => 'Override border radius specifically for text inputs and selects.',
    'resource.fields.shadow_intensity.label'       => 'Shadow Intensity',
    'resource.fields.shadow_intensity.helper_text' => 'Box shadow on cards and dropdown panels.',
    'resource.fields.badge_shape.label'       => 'Badge Shape',
    'resource.fields.badge_shape.helper_text' => 'Border radius and padding for badges.',

    // ─── Custom CSS Fields ─────
    'resource.fields.custom_css.label'       => 'Custom CSS',
    'resource.fields.custom_css.helper_text' => 'Custom CSS will be injected into your panel. <script> tags are automatically removed for security.',

    // ─── Layout Fields ─────
    'resource.fields.topbar.label'       => 'Top Bar',
    'resource.fields.topbar.helper_text' => 'Show the top bar with user menu and notifications.',
    'resource.fields.top_navigation.label'       => 'Top Navigation',
    'resource.fields.top_navigation.helper_text' => 'Move navigation from sidebar to top bar. Disables sidebar.',
    'resource.fields.sidebar_collapsible.label'       => 'Collapsible Sidebar',
    'resource.fields.sidebar_collapsible.helper_text' => 'Allows sidebar to collapse to icons only.',
    'resource.fields.sidebar_fully_collapsible.label'       => 'Fully Collapsible Sidebar',
    'resource.fields.sidebar_fully_collapsible.helper_text' => 'Allows sidebar to hide completely.',
    'resource.fields.collapsible_navigation_groups.label'       => 'Collapsible Navigation Groups',
    'resource.fields.collapsible_navigation_groups.helper_text' => 'Allow navigation groups to be expanded/collapsed.',
    'resource.fields.breadcrumbs.label'       => 'Breadcrumbs',
    'resource.fields.breadcrumbs.helper_text' => 'Show breadcrumb navigation.',
    'resource.fields.content_width.label'       => 'Content Width',
    'resource.fields.content_width.helper_text' => 'Max-width of the main content area.',
    'resource.fields.sidebar_width.label'       => 'Sidebar Width',
    'resource.fields.sidebar_width.helper_text' => 'Fixed width of the navigation sidebar.',
    'resource.fields.page_heading_size.label'       => 'Page Heading Size',
    'resource.fields.page_heading_size.helper_text' => 'Font size of page headings (h1).',
    'resource.fields.nav_item_spacing.label'       => 'Navigation Item Spacing',
    'resource.fields.nav_item_spacing.helper_text' => 'Vertical padding between sidebar navigation items.',
    'resource.fields.footer_text.label'       => 'Footer Text',
    'resource.fields.footer_text.placeholder' => 'ACME Admin Portal',
    'resource.fields.footer_text.helper_text' => 'Text displayed in the panel footer. Leave empty to hide.',
    'resource.fields.footer_links.label'           => 'Footer Links',
    'resource.fields.footer_links.link_label.label' => 'Label',
    'resource.fields.footer_links.link_url.label'  => 'URL',
    'resource.fields.footer_links.add_link'        => 'Add link',

    // ─── Advanced Fields ─────
    'resource.fields.unsaved_changes.label'       => 'Unsaved Changes Alerts',
    'resource.fields.unsaved_changes.helper_text' => 'Warn before leaving pages with unsaved changes.',
    'resource.fields.spa_mode.label'       => 'SPA Mode',
    'resource.fields.spa_mode.helper_text' => 'Single-page application mode for faster navigation.',
    'resource.fields.database_notifications.label'       => 'Database Notifications',
    'resource.fields.database_notifications.helper_text' => 'Enable database notifications in the topbar/sidebar.',
    'resource.fields.polling_interval.label' => 'Polling Interval',
    'resource.fields.font_scale.label'       => 'Font Scale',
    'resource.fields.font_scale.helper_text' => 'Global font size multiplier for accessibility or density.',
    'resource.fields.form_density.label'       => 'Form Density',
    'resource.fields.form_density.helper_text' => 'Padding and spacing within form sections and fields.',
    'resource.fields.table_row_density.label'       => 'Table Row Density',
    'resource.fields.table_row_density.helper_text' => 'Vertical padding of table rows.',
    'resource.fields.modal_size.label'       => 'Default Modal Size',
    'resource.fields.modal_size.helper_text' => 'Default max-width for modal dialogs.',
    'resource.fields.transition_speed.label'       => 'Transition Speed',
    'resource.fields.transition_speed.helper_text' => 'Duration of CSS transitions on buttons, dropdowns, modals, and sidebar.',

    // ─── Shared Options ─────
    'resource.options.default'     => 'Default',
    'resource.options.none'        => 'None',
    'resource.options.small'       => 'Small',
    'resource.options.medium'      => 'Medium',
    'resource.options.large'       => 'Large',
    'resource.options.pill'        => 'Pill',
    'resource.options.inherit'     => 'Inherit',
    'resource.options.sharp'       => 'Sharp',
    'resource.options.rounded'     => 'Rounded',
    'resource.options.subtle'      => 'Subtle',
    'resource.options.pronounced'  => 'Pronounced',
    'resource.options.compact'     => 'Compact',
    'resource.options.spacious'    => 'Spacious',
    'resource.options.fast'        => 'Fast',
    'resource.options.slow'        => 'Slow',

    // ─── Layout-Specific Options ─────
    'resource.options.content_width.1024' => '1024px (Narrow)',
    'resource.options.content_width.1280' => '1280px',
    'resource.options.content_width.full' => 'Full Width',
    'resource.options.sidebar_width.320'  => 'Default (320px)',
    'resource.options.sidebar_width.260'  => '260px',
    'resource.options.sidebar_width.300'  => '300px',
    'resource.options.sidebar_width.340'  => '340px',
    'resource.options.page_heading_size.small' => 'Small',
    'resource.options.page_heading_size.large' => 'Large',
    'resource.options.nav_item_spacing.default'  => 'Default',
    'resource.options.nav_item_spacing.compact'  => 'Compact',
    'resource.options.nav_item_spacing.spacious' => 'Spacious',

    // ─── Advanced-Specific Options ─────
    'resource.options.polling_interval.30s' => 'Default (30s)',
    'resource.options.polling_interval.10s' => '10 seconds',
    'resource.options.polling_interval.60s' => '1 minute',
    'resource.options.polling_interval.2m'  => '2 minutes',
    'resource.options.polling_interval.5m'  => '5 minutes',
    'resource.options.font_scale.90'  => '90% (Compact)',
    'resource.options.font_scale.100' => '100% (Default)',
    'resource.options.font_scale.110' => '110% (Large)',
    'resource.options.font_scale.120' => '120% (Extra Large)',
    'resource.options.modal_size.small'  => 'Small (480px)',
    'resource.options.modal_size.medium' => 'Medium (640px)',
    'resource.options.modal_size.large'  => 'Large (800px)',
    'resource.options.modal_size.xl'     => 'Extra Large (1024px)',
    'resource.options.transition_speed.none' => 'None',

    // ─── Table Columns ─────
    'resource.table.columns.logo'    => 'Logo',
    'resource.table.columns.brand'   => 'Brand',
    'resource.table.columns.updated' => 'Last Updated',

    // ─── Fonts ─────
    'fonts.default_suffix' => '(Default)',

    // ─── Commands: Install ─────
    'commands.install.description'    => 'Install Filament White-Label — publish config and migrations',
    'commands.install.banner'         => 'Filament White-Label Installer',
    'commands.install.success'        => 'Filament White-Label installed successfully.',
    'commands.install.next_steps'     => 'Next steps:',
    'commands.install.step_1'         => '1. Review the published migration in database/migrations/',
    'commands.install.step_2'         => '2. Run php artisan migrate',
    'commands.install.step_3'         => '3. Review the config at config/filament-white-label.php',
    'commands.install.step_4'         => '4. Add traits to your Tenant model and PanelProvider',
    'commands.install.docs'           => '📖 Documentation: https://github.com/ashrafic/filament-white-label',
    'commands.install.config_skipped'    => '⏭  Config already published — skipping.',
    'commands.install.config_published'  => '✅ Config published to config/filament-white-label.php',
    'commands.install.migration_published' => '✅ Migration published to database/migrations/',

    // ─── Commands: Clear Cache ─────
    'commands.clear_cache.description'   => 'Clear the white-label brand settings cache',
    'commands.clear_cache.option_tenant' => 'Clear cache for a specific tenant ID',
    'commands.clear_cache.option_panel'  => 'Clear cache for a specific panel ID',
    'commands.clear_cache.success'       => 'White-label cache cleared.',
];
```

- [ ] **Step 2: Verify file exists and is valid PHP**

Run: `php -l lang/en/filament-white-label.php`
Expected: `No syntax errors detected in lang/en/filament-white-label.php`

- [ ] **Step 3: Commit**

```bash
git add lang/en/filament-white-label.php
git commit -m "feat: add English translation file with all ~170 keys"
```

---

### Task 2: Register translations in ServiceProvider

**Files:**
- Modify: `src/Providers/FilamentWhiteLabelServiceProvider.php`

- [ ] **Step 1: Add loadTranslationsFrom and publishes in boot()**

Add these lines inside the `boot()` method, after the existing `loadViewsFrom` line and before the first `publishes` call:

```php
$this->loadTranslationsFrom(__DIR__.'/../../lang', 'filament-white-label');

$this->publishes([
    __DIR__.'/../../lang' => $this->app->langPath('vendor/filament-white-label'),
], 'filament-white-label-translations');
```

The full boot() method after the edit should start like:

```php
public function boot(): void
{
    $this->loadViewsFrom(__DIR__.'/../../resources/views', 'filament-white-label');

    $this->loadTranslationsFrom(__DIR__.'/../../lang', 'filament-white-label');

    $this->publishes([
        __DIR__.'/../../lang' => $this->app->langPath('vendor/filament-white-label'),
    ], 'filament-white-label-translations');

    $this->publishes([
        __DIR__.'/../../config/filament-white-label.php' => config_path('filament-white-label.php'),
    ], 'filament-white-label-config');

    // ... rest unchanged
```

- [ ] **Step 2: Verify with PHP syntax check**

Run: `php -l src/Providers/FilamentWhiteLabelServiceProvider.php`
Expected: `No syntax errors detected`

- [ ] **Step 3: Commit**

```bash
git add src/Providers/FilamentWhiteLabelServiceProvider.php
git commit -m "feat: register translations in service provider with publish tag"
```

---

### Task 3: Make FontService "(Default)" translatable

**Files:**
- Modify: `src/Fonts/FontService.php:12`

- [ ] **Step 1: Replace the hardcoded "(Default)" suffix**

Change line 12 from:
```php
'Inter' => 'Inter (Default)',
```
To:
```php
'Inter' => 'Inter '.__('filament-white-label::filament-white-label.fonts.default_suffix'),
```

- [ ] **Step 2: Verify syntax**

Run: `php -l src/Fonts/FontService.php`
Expected: `No syntax errors detected`

- [ ] **Step 3: Commit**

```bash
git add src/Fonts/FontService.php
git commit -m "feat: make font default suffix translatable"
```

---

### Task 4: Translate WhiteLabelSettingsResource (navigation, form, table)

**Files:**
- Modify: `src/Resources/WhiteLabelSettingsResource.php`

- [ ] **Step 1: Translate navigation label, label, pluralLabel**

Replace lines 36-40:

```php
protected static ?string $navigationLabel = 'White Label';

protected static ?string $label = 'White Label Settings';

protected static ?string $pluralLabel = 'White Label Settings';
```

With:

```php
public static function getNavigationLabel(): string
{
    return __('filament-white-label::filament-white-label.resource.navigation.label');
}

public static function getLabel(): ?string
{
    return __('filament-white-label::filament-white-label.resource.label.singular');
}

public static function getPluralLabel(): ?string
{
    return __('filament-white-label::filament-white-label.resource.label.plural');
}
```

Note: Remove the `$navigationLabel`, `$label`, and `$pluralLabel` static properties and replace with the method overrides since `__()` cannot be used in property initializers.

- [ ] **Step 2: Translate sub-navigation items**

Replace the three `NavigationItem::make()` calls in `getRecordSubNavigation()`:

```php
return [
    NavigationItem::make(__('filament-white-label::filament-white-label.resource.sub_navigation.brand'))
        ->label(__('filament-white-label::filament-white-label.resource.sub_navigation.brand'))
        ->icon('heroicon-o-paint-brush')
        ->url(fn () => static::getUrl('index'))
        ->isActiveWhen(fn () => $page instanceof EditWhiteLabelSettings),
    NavigationItem::make(__('filament-white-label::filament-white-label.resource.sub_navigation.layout'))
        ->label(__('filament-white-label::filament-white-label.resource.sub_navigation.layout'))
        ->icon('heroicon-o-rectangle-group')
        ->url(fn () => static::getUrl('layout'))
        ->isActiveWhen(fn () => $page instanceof EditLayoutSettings),
    NavigationItem::make(__('filament-white-label::filament-white-label.resource.sub_navigation.advanced'))
        ->label(__('filament-white-label::filament-white-label.resource.sub_navigation.advanced'))
        ->icon('heroicon-o-cog-6-tooth')
        ->url(fn () => static::getUrl('advanced'))
        ->isActiveWhen(fn () => $page instanceof EditAdvancedSettings),
];
```

- [ ] **Step 3: Translate all sections in form()**

Replace section headings and field labels/helpers. Here are all the replacements needed in the `form()` method (lines 78-174):

**Section: 'Brand Identity'** → `__('filament-white-label::filament-white-label.resource.sections.brand_identity')`

**Section: 'Colors'** → `__('filament-white-label::filament-white-label.resource.sections.colors')`

**Section: 'Typography'** → `__('filament-white-label::filament-white-label.resource.sections.typography')`

**Section: 'Custom CSS'** → `__('filament-white-label::filament-white-label.resource.sections.custom_css')`

**Field labels and helpers:**
- `.label('Brand Name')` → `.label(__('filament-white-label::filament-white-label.resource.fields.brand_name.label'))`
- `.label('Logo Height')` → `.label(__('filament-white-label::filament-white-label.resource.fields.logo_height.label'))`
- `.placeholder('2.5rem')` → `.placeholder(__('filament-white-label::filament-white-label.resource.fields.logo_height.placeholder'))`
- `.helperText('CSS height value...')` → `.helperText(__('filament-white-label::filament-white-label.resource.fields.logo_height.helper_text'))`
- `.label('Logo (Light)')` → `.label(__('filament-white-label::filament-white-label.resource.fields.logo_light.label'))`
- `.label('Logo (Dark)')` → `.label(__('filament-white-label::filament-white-label.resource.fields.logo_dark.label'))`
- `.helperText('Falls back to light...')` → `.helperText(__('filament-white-label::filament-white-label.resource.fields.logo_dark.helper_text'))`
- `.label('Favicon')` → `.label(__('filament-white-label::filament-white-label.resource.fields.favicon.label'))`
- `.label('Primary')` → `.label(__('filament-white-label::filament-white-label.resource.fields.colors.primary.label'))`
- `.label('Secondary')` → `.label(__('filament-white-label::filament-white-label.resource.fields.colors.secondary.label'))`
- `.label('Danger')` → `.label(__('filament-white-label::filament-white-label.resource.fields.colors.danger.label'))`
- `.label('Warning')` → `.label(__('filament-white-label::filament-white-label.resource.fields.colors.warning.label'))`
- `.label('Success')` → `.label(__('filament-white-label::filament-white-label.resource.fields.colors.success.label'))`
- `.label('Info')` → `.label(__('filament-white-label::filament-white-label.resource.fields.colors.info.label'))`
- `.label('Font Family')` → `.label(__('filament-white-label::filament-white-label.resource.fields.font_family.label'))`
- `.label('Custom CSS')` → `.label(__('filament-white-label::filament-white-label.resource.fields.custom_css.label'))`
- `.helperText('Custom CSS will be injected...')` → `.helperText(__('filament-white-label::filament-white-label.resource.fields.custom_css.helper_text'))`

- [ ] **Step 4: Translate table columns**

Replace table column labels (lines 181-183):

```php
ImageColumn::make('metadata.logo_path')
    ->label(__('filament-white-label::filament-white-label.resource.table.columns.logo'))
    ->circular()->size(40),
TextColumn::make('metadata.brand_name')
    ->label(__('filament-white-label::filament-white-label.resource.table.columns.brand'))
    ->searchable(),
TextColumn::make('updated_at')
    ->label(__('filament-white-label::filament-white-label.resource.table.columns.updated'))
    ->dateTime(),
```

- [ ] **Step 5: Verify syntax**

Run: `php -l src/Resources/WhiteLabelSettingsResource.php`
Expected: `No syntax errors detected`

- [ ] **Step 6: Commit**

```bash
git add src/Resources/WhiteLabelSettingsResource.php
git commit -m "feat: translate WhiteLabelSettingsResource navigation, form, and table strings"
```

---

### Task 5: Translate EditWhiteLabelSettings page

**Files:**
- Modify: `src/Resources/WhiteLabelSettingsResource/Pages/EditWhiteLabelSettings.php`

- [ ] **Step 1: Translate page title and navigation label**

Replace lines 26-28:

```php
public static function getTitle(): string
{
    return __('filament-white-label::filament-white-label.resource.page.brand.title');
}

public static function getNavigationLabel(): string
{
    return __('filament-white-label::filament-white-label.resource.page.brand.nav_label');
}
```

Remove the static properties `$title` and `$navigationLabel`.

- [ ] **Step 2: Translate all section headings**

Replace all `Section::make()` calls in `form()`:

- `Section::make('Brand Identity')` → `Section::make(__('filament-white-label::filament-white-label.resource.sections.brand_identity'))`
- `Section::make('Colors')` → `Section::make(__('filament-white-label::filament-white-label.resource.sections.colors'))`
- `Section::make('Typography')` → `Section::make(__('filament-white-label::filament-white-label.resource.sections.typography'))`
- `Section::make('Styling')` → `Section::make(__('filament-white-label::filament-white-label.resource.sections.styling'))`
- `Section::make('Custom CSS')` → `Section::make(__('filament-white-label::filament-white-label.resource.sections.custom_css'))`

- [ ] **Step 3: Translate all fieldset labels**

Replace all `Fieldset::make()` calls:

- `Fieldset::make('Primary')` → `Fieldset::make(__('filament-white-label::filament-white-label.resource.fields.colors.primary.label'))`
- `Fieldset::make('Secondary')` → `Fieldset::make(__('filament-white-label::filament-white-label.resource.fields.colors.secondary.label'))`
- `Fieldset::make('Danger')` → `Fieldset::make(__('filament-white-label::filament-white-label.resource.fields.colors.danger.label'))`
- `Fieldset::make('Warning')` → `Fieldset::make(__('filament-white-label::filament-white-label.resource.fields.colors.warning.label'))`
- `Fieldset::make('Success')` → `Fieldset::make(__('filament-white-label::filament-white-label.resource.fields.colors.success.label'))`
- `Fieldset::make('Info')` → `Fieldset::make(__('filament-white-label::filament-white-label.resource.fields.colors.info.label'))`

- [ ] **Step 4: Translate all field labels, helper texts, and placeholders**

Brand fields:
- `.label('Brand Name')` → `.label(__('filament-white-label::filament-white-label.resource.fields.brand_name.label'))`
- `.label('Logo Height')` → `.label(__('filament-white-label::filament-white-label.resource.fields.logo_height.label'))`
- `.placeholder('2.5rem')` → `.placeholder(__('filament-white-label::filament-white-label.resource.fields.logo_height.placeholder'))`
- `.helperText('CSS height value...')` → `.helperText(__('filament-white-label::filament-white-label.resource.fields.logo_height.helper_text'))`
- `.label('Logo (Light)')` → `.label(__('filament-white-label::filament-white-label.resource.fields.logo_light.label'))`
- `.label('Logo (Dark)')` → `.label(__('filament-white-label::filament-white-label.resource.fields.logo_dark.label'))`
- `.helperText('Falls back to light...')` → `.helperText(__('filament-white-label::filament-white-label.resource.fields.logo_dark.helper_text'))`
- `.label('Favicon')` → `.label(__('filament-white-label::filament-white-label.resource.fields.favicon.label'))`

Color picker/select labels:
- `.label('Palette')` → `.label(__('filament-white-label::filament-white-label.resource.fields.colors.palette.label'))`
- `.label('Hex')` → `.label(__('filament-white-label::filament-white-label.resource.fields.colors.hex.label'))`

Typography:
- `.label('Font Family')` → `.label(__('filament-white-label::filament-white-label.resource.fields.font_family.label'))`

Styling fields (border_radius, input_border_radius, shadow_intensity, badge_shape):
- `.label('Border Radius')` → `.label(__('filament-white-label::filament-white-label.resource.fields.border_radius.label'))`
- `.helperText('Rounded corners...')` → `.helperText(__('filament-white-label::filament-white-label.resource.fields.border_radius.helper_text'))`
- `.label('Input Border Radius')` → `.label(__('filament-white-label::filament-white-label.resource.fields.input_border_radius.label'))`
- `.helperText('Override border...')` → `.helperText(__('filament-white-label::filament-white-label.resource.fields.input_border_radius.helper_text'))`
- `.label('Shadow Intensity')` → `.label(__('filament-white-label::filament-white-label.resource.fields.shadow_intensity.label'))`
- `.helperText('Box shadow...')` → `.helperText(__('filament-white-label::filament-white-label.resource.fields.shadow_intensity.helper_text'))`
- `.label('Badge Shape')` → `.label(__('filament-white-label::filament-white-label.resource.fields.badge_shape.label'))`
- `.helperText('Border radius...')` → `.helperText(__('filament-white-label::filament-white-label.resource.fields.badge_shape.helper_text'))`

Custom CSS:
- `.label('Custom CSS')` → `.label(__('filament-white-label::filament-white-label.resource.fields.custom_css.label'))`
- `.helperText('Custom CSS will be...')` → `.helperText(__('filament-white-label::filament-white-label.resource.fields.custom_css.helper_text'))`

- [ ] **Step 5: Translate select option arrays**

Replace inline option arrays with translatable closures:

**Border Radius options:**
```php
->options(fn () => [
    'default' => __('filament-white-label::filament-white-label.resource.options.default'),
    'none'    => __('filament-white-label::filament-white-label.resource.options.none'),
    'small'   => __('filament-white-label::filament-white-label.resource.options.small'),
    'medium'  => __('filament-white-label::filament-white-label.resource.options.medium'),
    'large'   => __('filament-white-label::filament-white-label.resource.options.large'),
    'pill'    => __('filament-white-label::filament-white-label.resource.options.pill'),
])
```

**Input Border Radius options:**
```php
->options(fn () => [
    null        => __('filament-white-label::filament-white-label.resource.options.inherit'),
    'default'   => __('filament-white-label::filament-white-label.resource.options.default'),
    'none'      => __('filament-white-label::filament-white-label.resource.options.none'),
    'small'     => __('filament-white-label::filament-white-label.resource.options.small'),
    'medium'    => __('filament-white-label::filament-white-label.resource.options.medium'),
    'large'     => __('filament-white-label::filament-white-label.resource.options.large'),
    'pill'      => __('filament-white-label::filament-white-label.resource.options.pill'),
])
```

**Shadow Intensity options:**
```php
->options(fn () => [
    'default'    => __('filament-white-label::filament-white-label.resource.options.default'),
    'none'       => __('filament-white-label::filament-white-label.resource.options.none'),
    'subtle'     => __('filament-white-label::filament-white-label.resource.options.subtle'),
    'pronounced' => __('filament-white-label::filament-white-label.resource.options.pronounced'),
])
```

**Badge Shape options:**
```php
->options(fn () => [
    'default' => __('filament-white-label::filament-white-label.resource.options.default'),
    'sharp'   => __('filament-white-label::filament-white-label.resource.options.sharp'),
    'rounded' => __('filament-white-label::filament-white-label.resource.options.rounded'),
    'pill'    => __('filament-white-label::filament-white-label.resource.options.pill'),
])
```

**Palette options** — translate 'Custom hex...':
```php
$options = ['custom' => __('filament-white-label::filament-white-label.resource.fields.colors.custom_hex')];
```

- [ ] **Step 6: Verify syntax**

Run: `php -l src/Resources/WhiteLabelSettingsResource/Pages/EditWhiteLabelSettings.php`
Expected: `No syntax errors detected`

- [ ] **Step 7: Commit**

```bash
git add src/Resources/WhiteLabelSettingsResource/Pages/EditWhiteLabelSettings.php
git commit -m "feat: translate EditWhiteLabelSettings page strings"
```

---

### Task 6: Translate EditLayoutSettings page

**Files:**
- Modify: `src/Resources/WhiteLabelSettingsResource/Pages/EditLayoutSettings.php`

- [ ] **Step 1: Translate page title and navigation label**

Replace lines 21-23 with method overrides:

```php
public static function getTitle(): string
{
    return __('filament-white-label::filament-white-label.resource.page.layout.title');
}

public static function getNavigationLabel(): string
{
    return __('filament-white-label::filament-white-label.resource.page.layout.nav_label');
}
```

Remove the `$title` and `$navigationLabel` static properties.

- [ ] **Step 2: Translate all section headings**

- `Section::make('Navigation')` → `Section::make(__('filament-white-label::filament-white-label.resource.sections.navigation'))`
- `Section::make('Sidebar')` → `Section::make(__('filament-white-label::filament-white-label.resource.sections.sidebar'))`
- `Section::make('Display')` → `Section::make(__('filament-white-label::filament-white-label.resource.sections.display'))`
- `Section::make('Dimensions')` → `Section::make(__('filament-white-label::filament-white-label.resource.sections.dimensions'))`
- `Section::make('Footer')` → `Section::make(__('filament-white-label::filament-white-label.resource.sections.footer'))`

- [ ] **Step 3: Translate all field labels and helper texts**

Toggle fields:
- `.label('Top Bar')` → `.label(__('filament-white-label::filament-white-label.resource.fields.topbar.label'))`
- `.helperText('Show the top bar...')` → `.helperText(__('filament-white-label::filament-white-label.resource.fields.topbar.helper_text'))`
- `.label('Top Navigation')` → `.label(__('filament-white-label::filament-white-label.resource.fields.top_navigation.label'))`
- `.helperText('Move navigation...')` → `.helperText(__('filament-white-label::filament-white-label.resource.fields.top_navigation.helper_text'))`
- `.label('Collapsible Sidebar')` → `.label(__('filament-white-label::filament-white-label.resource.fields.sidebar_collapsible.label'))`
- `.helperText('Allows sidebar to collapse...')` → `.helperText(__('filament-white-label::filament-white-label.resource.fields.sidebar_collapsible.helper_text'))`
- `.label('Fully Collapsible Sidebar')` → `.label(__('filament-white-label::filament-white-label.resource.fields.sidebar_fully_collapsible.label'))`
- `.helperText('Allows sidebar to hide...')` → `.helperText(__('filament-white-label::filament-white-label.resource.fields.sidebar_fully_collapsible.helper_text'))`
- `.label('Collapsible Navigation Groups')` → `.label(__('filament-white-label::filament-white-label.resource.fields.collapsible_navigation_groups.label'))`
- `.helperText('Allow navigation groups...')` → `.helperText(__('filament-white-label::filament-white-label.resource.fields.collapsible_navigation_groups.helper_text'))`
- `.label('Breadcrumbs')` → `.label(__('filament-white-label::filament-white-label.resource.fields.breadcrumbs.label'))`
- `.helperText('Show breadcrumb navigation.')` → `.helperText(__('filament-white-label::filament-white-label.resource.fields.breadcrumbs.helper_text'))`

Select fields:
- `.label('Content Width')` → `.label(__('filament-white-label::filament-white-label.resource.fields.content_width.label'))`
- `.helperText('Max-width...')` → `.helperText(__('filament-white-label::filament-white-label.resource.fields.content_width.helper_text'))`
- `.label('Sidebar Width')` → `.label(__('filament-white-label::filament-white-label.resource.fields.sidebar_width.label'))`
- `.helperText('Fixed width...')` → `.helperText(__('filament-white-label::filament-white-label.resource.fields.sidebar_width.helper_text'))`
- `.label('Page Heading Size')` → `.label(__('filament-white-label::filament-white-label.resource.fields.page_heading_size.label'))`
- `.helperText('Font size...')` → `.helperText(__('filament-white-label::filament-white-label.resource.fields.page_heading_size.helper_text'))`
- `.label('Navigation Item Spacing')` → `.label(__('filament-white-label::filament-white-label.resource.fields.nav_item_spacing.label'))`
- `.helperText('Vertical padding...')` → `.helperText(__('filament-white-label::filament-white-label.resource.fields.nav_item_spacing.helper_text'))`

Footer fields:
- `.label('Footer Text')` → `.label(__('filament-white-label::filament-white-label.resource.fields.footer_text.label'))`
- `.placeholder('ACME Admin Portal')` → `.placeholder(__('filament-white-label::filament-white-label.resource.fields.footer_text.placeholder'))`
- `.helperText('Text displayed...')` → `.helperText(__('filament-white-label::filament-white-label.resource.fields.footer_text.helper_text'))`
- `.label('Footer Links')` → `.label(__('filament-white-label::filament-white-label.resource.fields.footer_links.label'))`
- `.label('Label')` (repeater field) → `.label(__('filament-white-label::filament-white-label.resource.fields.footer_links.link_label.label'))`
- `.label('URL')` (repeater field) → `.label(__('filament-white-label::filament-white-label.resource.fields.footer_links.link_url.label'))`
- `.addActionLabel('Add link')` → `.addActionLabel(__('filament-white-label::filament-white-label.resource.fields.footer_links.add_link'))`

- [ ] **Step 4: Translate select options**

**Content Width:**
```php
->options(fn () => [
    null     => __('filament-white-label::filament-white-label.resource.options.default'),
    '1024px' => __('filament-white-label::filament-white-label.resource.options.content_width.1024'),
    '1280px' => __('filament-white-label::filament-white-label.resource.options.content_width.1280'),
    'full'   => __('filament-white-label::filament-white-label.resource.options.content_width.full'),
])
```

**Sidebar Width:**
```php
->options(fn () => [
    null     => __('filament-white-label::filament-white-label.resource.options.sidebar_width.320'),
    '260px'  => __('filament-white-label::filament-white-label.resource.options.sidebar_width.260'),
    '300px'  => __('filament-white-label::filament-white-label.resource.options.sidebar_width.300'),
    '340px'  => __('filament-white-label::filament-white-label.resource.options.sidebar_width.340'),
])
```

**Page Heading Size:**
```php
->options(fn () => [
    'default' => __('filament-white-label::filament-white-label.resource.options.default'),
    'small'   => __('filament-white-label::filament-white-label.resource.options.page_heading_size.small'),
    'large'   => __('filament-white-label::filament-white-label.resource.options.page_heading_size.large'),
])
```

**Navigation Item Spacing:**
```php
->options(fn () => [
    'default'  => __('filament-white-label::filament-white-label.resource.options.nav_item_spacing.default'),
    'compact'  => __('filament-white-label::filament-white-label.resource.options.nav_item_spacing.compact'),
    'spacious' => __('filament-white-label::filament-white-label.resource.options.nav_item_spacing.spacious'),
])
```

- [ ] **Step 5: Verify syntax**

Run: `php -l src/Resources/WhiteLabelSettingsResource/Pages/EditLayoutSettings.php`
Expected: `No syntax errors detected`

- [ ] **Step 6: Commit**

```bash
git add src/Resources/WhiteLabelSettingsResource/Pages/EditLayoutSettings.php
git commit -m "feat: translate EditLayoutSettings page strings"
```

---

### Task 7: Translate EditAdvancedSettings page

**Files:**
- Modify: `src/Resources/WhiteLabelSettingsResource/Pages/EditAdvancedSettings.php`

- [ ] **Step 1: Translate page title and navigation label**

Replace lines 19-21 with method overrides:

```php
public static function getTitle(): string
{
    return __('filament-white-label::filament-white-label.resource.page.advanced.title');
}

public static function getNavigationLabel(): string
{
    return __('filament-white-label::filament-white-label.resource.page.advanced.nav_label');
}
```

Remove the `$title` and `$navigationLabel` static properties.

- [ ] **Step 2: Translate all section headings**

- `Section::make('Behavior')` → `Section::make(__('filament-white-label::filament-white-label.resource.sections.behavior'))`
- `Section::make('Notifications')` → `Section::make(__('filament-white-label::filament-white-label.resource.sections.notifications'))`
- `Section::make('Styling')` → `Section::make(__('filament-white-label::filament-white-label.resource.sections.styling'))`

- [ ] **Step 3: Translate all field labels and helper texts**

Toggle fields:
- `.label('Unsaved Changes Alerts')` → `.label(__('filament-white-label::filament-white-label.resource.fields.unsaved_changes.label'))`
- `.helperText('Warn before leaving...')` → `.helperText(__('filament-white-label::filament-white-label.resource.fields.unsaved_changes.helper_text'))`
- `.label('SPA Mode')` → `.label(__('filament-white-label::filament-white-label.resource.fields.spa_mode.label'))`
- `.helperText('Single-page...')` → `.helperText(__('filament-white-label::filament-white-label.resource.fields.spa_mode.helper_text'))`
- `.label('Database Notifications')` → `.label(__('filament-white-label::filament-white-label.resource.fields.database_notifications.label'))`
- `.helperText('Enable database...')` → `.helperText(__('filament-white-label::filament-white-label.resource.fields.database_notifications.helper_text'))`

Select fields:
- `.label('Polling Interval')` → `.label(__('filament-white-label::filament-white-label.resource.fields.polling_interval.label'))`
- `.label('Font Scale')` → `.label(__('filament-white-label::filament-white-label.resource.fields.font_scale.label'))`
- `.helperText('Global font size...')` → `.helperText(__('filament-white-label::filament-white-label.resource.fields.font_scale.helper_text'))`
- `.label('Form Density')` → `.label(__('filament-white-label::filament-white-label.resource.fields.form_density.label'))`
- `.helperText('Padding and spacing...')` → `.helperText(__('filament-white-label::filament-white-label.resource.fields.form_density.helper_text'))`
- `.label('Table Row Density')` → `.label(__('filament-white-label::filament-white-label.resource.fields.table_row_density.label'))`
- `.helperText('Vertical padding...')` → `.helperText(__('filament-white-label::filament-white-label.resource.fields.table_row_density.helper_text'))`
- `.label('Default Modal Size')` → `.label(__('filament-white-label::filament-white-label.resource.fields.modal_size.label'))`
- `.helperText('Default max-width...')` → `.helperText(__('filament-white-label::filament-white-label.resource.fields.modal_size.helper_text'))`
- `.label('Transition Speed')` → `.label(__('filament-white-label::filament-white-label.resource.fields.transition_speed.label'))`
- `.helperText('Duration of CSS...')` → `.helperText(__('filament-white-label::filament-white-label.resource.fields.transition_speed.helper_text'))`

- [ ] **Step 4: Translate select options**

**Polling Interval:**
```php
->options(fn () => [
    null   => __('filament-white-label::filament-white-label.resource.options.polling_interval.30s'),
    '10s'  => __('filament-white-label::filament-white-label.resource.options.polling_interval.10s'),
    '30s'  => __('filament-white-label::filament-white-label.resource.options.polling_interval.30s'),
    '60s'  => __('filament-white-label::filament-white-label.resource.options.polling_interval.60s'),
    '2m'   => __('filament-white-label::filament-white-label.resource.options.polling_interval.2m'),
    '5m'   => __('filament-white-label::filament-white-label.resource.options.polling_interval.5m'),
])
```

**Font Scale:**
```php
->options(fn () => [
    null   => __('filament-white-label::filament-white-label.resource.options.default'),
    '90%'  => __('filament-white-label::filament-white-label.resource.options.font_scale.90'),
    '100%' => __('filament-white-label::filament-white-label.resource.options.font_scale.100'),
    '110%' => __('filament-white-label::filament-white-label.resource.options.font_scale.110'),
    '120%' => __('filament-white-label::filament-white-label.resource.options.font_scale.120'),
])
```

Wait — the existing code uses `null => 'Default (100%)'` as the key. But looking at the current code more carefully:

```php
'options' => [
    null => 'Default (100%)',
    '90%' => '90% (Compact)',
    ...
]
```

We need to keep the null key. Let's use `null => __('...options.default')`:

```php
->options(fn () => [
    null   => __('filament-white-label::filament-white-label.resource.options.default'),
    '90%'  => __('filament-white-label::filament-white-label.resource.options.font_scale.90'),
    '100%' => __('filament-white-label::filament-white-label.resource.options.font_scale.100'),
    '110%' => __('filament-white-label::filament-white-label.resource.options.font_scale.110'),
    '120%' => __('filament-white-label::filament-white-label.resource.options.font_scale.120'),
])
```

**Form Density:**
```php
->options(fn () => [
    'default'  => __('filament-white-label::filament-white-label.resource.options.default'),
    'compact'  => __('filament-white-label::filament-white-label.resource.options.compact'),
    'spacious' => __('filament-white-label::filament-white-label.resource.options.spacious'),
])
```

**Table Row Density:**
```php
->options(fn () => [
    'default'  => __('filament-white-label::filament-white-label.resource.options.default'),
    'compact'  => __('filament-white-label::filament-white-label.resource.options.compact'),
    'spacious' => __('filament-white-label::filament-white-label.resource.options.spacious'),
])
```

**Modal Size:**
```php
->options(fn () => [
    'default'     => __('filament-white-label::filament-white-label.resource.options.default'),
    'small'       => __('filament-white-label::filament-white-label.resource.options.modal_size.small'),
    'medium'      => __('filament-white-label::filament-white-label.resource.options.modal_size.medium'),
    'large'       => __('filament-white-label::filament-white-label.resource.options.modal_size.large'),
    'extra-large' => __('filament-white-label::filament-white-label.resource.options.modal_size.xl'),
])
```

**Transition Speed:**
```php
->options(fn () => [
    'default' => __('filament-white-label::filament-white-label.resource.options.default'),
    'none'    => __('filament-white-label::filament-white-label.resource.options.transition_speed.none'),
    'fast'    => __('filament-white-label::filament-white-label.resource.options.fast'),
    'slow'    => __('filament-white-label::filament-white-label.resource.options.slow'),
])
```

- [ ] **Step 5: Verify syntax**

Run: `php -l src/Resources/WhiteLabelSettingsResource/Pages/EditAdvancedSettings.php`
Expected: `No syntax errors detected`

- [ ] **Step 6: Commit**

```bash
git add src/Resources/WhiteLabelSettingsResource/Pages/EditAdvancedSettings.php
git commit -m "feat: translate EditAdvancedSettings page strings"
```

---

### Task 8: Translate InstallWhiteLabelCommand

**Files:**
- Modify: `src/Commands/InstallWhiteLabelCommand.php`

- [ ] **Step 1: Translate command description and output strings**

Replace the `$description` property:
```php
protected $description = 'Install Filament White-Label — publish config and migrations';
```
With a method override:
```php
public function getDescription(): string
{
    return (string) __('filament-white-label::filament-white-label.commands.install.description');
}
```

Note: `$description` is a protected property from Illuminate\Console\Command. Since we can't use `__()` in property initializers, we need to convert this. However, `Command::getDescription()` doesn't exist by default — we should set it in the constructor instead.

Actually, the simplest approach: override the property in the constructor or just use a different approach. For artisan commands, the `$description` property IS read dynamically via the `setDescription()` method. Let's check...

Actually, in Laravel, `Command::$description` is used in `configure()` which calls `parent::configure()`. The Symfony Console component reads `$description` as a property before the constructor. So `__()` in a property initializer wouldn't work.

The cleanest approach: Override the `getDescription()` method is not standard. Instead, we can use `$this->setDescription()` in the constructor:

```php
public function __construct()
{
    parent::__construct();
    $this->setDescription((string) __('filament-white-label::filament-white-label.commands.install.description'));
}
```

But wait, the signature is also a property. The `$signature` property is fine as-is (it's a CLI name, not user-facing).

Let me actually just check what gets displayed. `$description` shows in `php artisan list`. So yes, it needs translation.

Simplest solution: add a constructor that calls `setDescription()`.

Actually wait - `__()` returns `string|array`. Let me cast with `(string)`.

Actually `__()` with a simple key with no replacements always returns a string. But to be safe with type hints, let me cast.

Now for the rest of the messages, replace all hardcoded strings in `handle()`:

```php
public function handle(): int
{
    $banner = __('filament-white-label::filament-white-label.commands.install.banner');

    $this->info(str_repeat('╔', 46));
    $this->info('║     '.str_pad($banner, 34).'║');
    $this->info(str_repeat('╚', 46));
    $this->newLine();

    // ... rest

    $this->newLine();
    $this->info(__('filament-white-label::filament-white-label.commands.install.success'));
    $this->newLine();
    $this->line(__('filament-white-label::filament-white-label.commands.install.next_steps'));
    $this->line('  '.__('filament-white-label::filament-white-label.commands.install.step_1'));
    $this->line('  '.__('filament-white-label::filament-white-label.commands.install.step_2'));
    $this->line('  '.__('filament-white-label::filament-white-label.commands.install.step_3'));
    $this->line('  '.__('filament-white-label::filament-white-label.commands.install.step_4'));
    $this->newLine();
    $this->line(__('filament-white-label::filament-white-label.commands.install.docs'));

    return self::SUCCESS;
}
```

And in `publishConfig()`:
```php
$this->line('  '.__('filament-white-label::filament-white-label.commands.install.config_skipped'));
```

```php
$this->line('  '.__('filament-white-label::filament-white-label.commands.install.config_published'));
```

And in `publishMigrations()`:
```php
$this->line('  '.__('filament-white-label::filament-white-label.commands.install.migration_published'));
```

- [ ] **Step 2: Verify syntax**

Run: `php -l src/Commands/InstallWhiteLabelCommand.php`
Expected: `No syntax errors detected`

- [ ] **Step 3: Commit**

```bash
git add src/Commands/InstallWhiteLabelCommand.php
git commit -m "feat: translate InstallWhiteLabelCommand strings"
```

---

### Task 9: Translate ClearWhiteLabelCacheCommand

**Files:**
- Modify: `src/Commands/ClearWhiteLabelCacheCommand.php`

- [ ] **Step 1: Translate command strings**

The `$signature` options use `{--tenant= : Clear cache ...}` and `{--panel= : Clear cache ...}` which are user-facing descriptions. These are in the signature string and can't use `__()`. However, we can reconstruct the signature using translatable descriptions.

Best approach: Use `configure()` to set the signature programmatically:

```php
protected function configure(): void
{
    $this->setName('white-label:clear-cache');

    $this->addOption(
        'tenant',
        null,
        \Symfony\Component\Console\Input\InputOption::VALUE_OPTIONAL,
        (string) __('filament-white-label::filament-white-label.commands.clear_cache.option_tenant'),
    );

    $this->addOption(
        'panel',
        null,
        \Symfony\Component\Console\Input\InputOption::VALUE_OPTIONAL,
        (string) __('filament-white-label::filament-white-label.commands.clear_cache.option_panel'),
    );

    $this->setDescription((string) __('filament-white-label::filament-white-label.commands.clear_cache.description'));
}
```

And replace the success message:
```php
$this->info(__('filament-white-label::filament-white-label.commands.clear_cache.success'));
```

- [ ] **Step 2: Also need to update the handle() to use addOption getters properly**

Since we're using `addOption` in `configure()`, the getter changes from `$this->option('tenant')` to `$this->option('tenant')` (same, works). No change needed in `handle()`.

- [ ] **Step 3: Verify syntax**

Run: `php -l src/Commands/ClearWhiteLabelCacheCommand.php`
Expected: `No syntax errors detected`

- [ ] **Step 4: Commit**

```bash
git add src/Commands/ClearWhiteLabelCacheCommand.php
git commit -m "feat: translate ClearWhiteLabelCacheCommand strings"
```

---

### Task 10: Run tests and verify

**Files:** None created or modified.

- [ ] **Step 1: Run all existing tests**

```bash
composer test
```
Expected: All tests pass — no behavioral changes, only string values changed (English strings are the same).

- [ ] **Step 2: Verify translation file loads**

```bash
php -r "require 'vendor/autoload.php'; echo __('filament-white-label::filament-white-label.resource.sections.brand_identity');"
```
Expected: Outputs `Brand Identity`

- [ ] **Step 3: Run lint**

```bash
composer lint
```
Expected: No formatting issues

