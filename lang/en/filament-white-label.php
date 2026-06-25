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
