# Translation Support — Design Spec

**Date:** 2026-06-24
**Status:** Approved
**Target:** Filament White-Label v1.x

## Summary

Add Laravel-native translation (i18n) support to the Filament White-Label package. All ~230 user-facing strings (labels, helper texts, section headings, select options, navigation labels, page titles, command output) become translatable via Laravel's `__()` helper. A single publishable English language file serves as the base for all other languages.

## Design Decisions

| Decision | Rationale |
|----------|-----------|
| Single translation file (`lang/en/filament-white-label.php`) | One file to publish, one file per language. Matches Laravel conventions. |
| Flat dot-notation keys | Most common Laravel pattern. Easy to grep. No deep nesting surprises. |
| English only as publishable base | Users copy and translate themselves. No need to ship translations we can't maintain. |
| Font names stay in `FontService` as-is | Font names are proper names. Only the "(Default)" suffix is translatable. |
| `loadTranslationsFrom()` in ServiceProvider | Standard Laravel package approach. Supports `vendor:publish` with `filament-white-label-translations` tag. |
| Namespace: `filament-white-label` | Matches package identity. Accessible as `__('filament-white-label::filament-white-label.key')`. |

## Files Created

| File | Purpose |
|------|---------|
| `lang/en/filament-white-label.php` | English translation array — ~170 keys covering all user-facing strings |

## Files Modified

| File | Changes |
|------|---------|
| `src/Providers/FilamentWhiteLabelServiceProvider.php` | Add `loadTranslationsFrom()` in `boot()`. Add `publishes()` for `filament-white-label-translations` tag. |
| `src/Resources/WhiteLabelSettingsResource.php` | Replace all hardcoded strings in `getNavigationLabel()`, `getLabel()`, `getPluralLabel()`, `getRecordSubNavigation()`, `form()`, `table()` with `__()` calls. |
| `src/Resources/WhiteLabelSettingsResource/Pages/EditWhiteLabelSettings.php` | Replace all hardcoded strings in `getTitle()`, `getNavigationLabel()`, form sections, fields, select options with `__()` calls. |
| `src/Resources/WhiteLabelSettingsResource/Pages/EditLayoutSettings.php` | Replace all hardcoded strings in `getTitle()`, `getNavigationLabel()`, form sections, fields, select options with `__()` calls. |
| `src/Resources/WhiteLabelSettingsResource/Pages/EditAdvancedSettings.php` | Replace all hardcoded strings in `getTitle()`, `getNavigationLabel()`, form sections, fields, select options with `__()` calls. |
| `src/Commands/InstallWhiteLabelCommand.php` | Replace all hardcoded output strings with `__()` calls. |
| `src/Commands/ClearWhiteLabelCacheCommand.php` | Replace all hardcoded output/description strings with `__()` calls. |
| `src/Fonts/FontService.php` | Pull "(Default)" suffix from translation. Keep font names as-is. |

## Key Format Convention

```
{category}.{section}.{suffix}

Examples:
  resource.navigation.group           → "White Label"
  resource.sections.brand_identity   → "Brand Identity"
  resource.fields.brand_name.label   → "Brand Name"
  resource.fields.logo_height.helper_text → "CSS height value..."
  resource.options.form_density.compact → "Compact"
  resource.table.columns.logo        → "Logo"
  commands.install.success           → "Filament White-Label installed successfully."
  fonts.default_suffix               → "(Default)"
```

Keys use `snake_case` consistently. Select option values are used as the key suffix (e.g., `resource.options.form_density.compact` for the "compact" option).

## Service Provider Integration

```php
// In boot() method:
$this->loadTranslationsFrom(__DIR__.'/../../lang', 'filament-white-label');

$this->publishes([
    __DIR__.'/../../lang' => $this->app->langPath('vendor/filament-white-label'),
], 'filament-white-label-translations');
```

Users publish with: `php artisan vendor:publish --tag=filament-white-label-translations`

## Usage Pattern in Code

```php
// Section heading
Section::make(__('filament-white-label::filament-white-label.resource.sections.brand_identity'))

// Field label
TextInput::make('metadata.brand_name')
    ->label(__('filament-white-label::filament-white-label.resource.fields.brand_name.label'))

// Helper text
->helperText(__('filament-white-label::filament-white-label.resource.fields.logo_height.helper_text'))

// Select options (closure-evaluated for runtime translation)
Select::make('metadata.border_radius')
    ->label(__('filament-white-label::filament-white-label.resource.fields.border_radius.label'))
    ->options(fn () => [
        'default' => __('filament-white-label::filament-white-label.resource.options.default'),
        'none'    => __('filament-white-label::filament-white-label.resource.options.none'),
        'small'   => __('filament-white-label::filament-white-label.resource.options.small'),
        'medium'  => __('filament-white-label::filament-white-label.resource.options.medium'),
        'large'   => __('filament-white-label::filament-white-label.resource.options.large'),
        'pill'    => __('filament-white-label::filament-white-label.resource.options.pill'),
    ])

// Font name with default marker
$label = $fontName;
if ($font === 'Inter') {
    $label .= ' ' . __('filament-white-label::filament-white-label.fonts.default_suffix');
}
```

## Testing Plan

- Verify English translation file loads correctly
- Verify all `__()` calls resolve to correct strings
- Verify `vendor:publish --tag=filament-white-label-translations` works
- Verify existing tests still pass (no behavioral changes)

## Edge Cases

- **Translation file missing**: Laravel's `__()` returns the key itself, so UI degrades gracefully.
- **User overrides translations**: Publishing to `lang/vendor/filament-white-label/` takes priority over package translations due to `loadTranslationsFrom` + `publishes` pattern.
- **JSON translations**: Not using. Only PHP array format to stay compatible with all Laravel versions.
