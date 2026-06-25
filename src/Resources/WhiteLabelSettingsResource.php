<?php

declare(strict_types=1);

namespace FilamentWhiteLabel\Resources;

use BackedEnum;
use Filament\Facades\Filament;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Navigation\NavigationItem;
use Filament\Pages\Page;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use FilamentWhiteLabel\Fonts\FontService;
use FilamentWhiteLabel\Models\WhiteLabelSettings;
use FilamentWhiteLabel\Resources\WhiteLabelSettingsResource\Pages\EditAdvancedSettings;
use FilamentWhiteLabel\Resources\WhiteLabelSettingsResource\Pages\EditLayoutSettings;
use FilamentWhiteLabel\Resources\WhiteLabelSettingsResource\Pages\EditWhiteLabelSettings;
use FilamentWhiteLabel\Services\WhiteLabel;

class WhiteLabelSettingsResource extends Resource
{
    protected static ?string $model = WhiteLabelSettings::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-swatch';

    public static function getNavigationLabel(): string
    {
        return __('filament-white-label::resource.navigation.label');
    }

    public static function getLabel(): ?string
    {
        return __('filament-white-label::resource.label.singular');
    }

    public static function getPluralLabel(): ?string
    {
        return __('filament-white-label::resource.label.plural');
    }

    public static function getNavigationGroup(): ?string
    {
        return null;
    }

    public static function getNavigationSort(): ?int
    {
        return config('filament-white-label.ui.navigation_sort', 10);
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        return [
            NavigationItem::make(__('filament-white-label::resource.sub_navigation.brand'))
                ->label(__('filament-white-label::resource.sub_navigation.brand'))
                ->icon('heroicon-o-paint-brush')
                ->url(fn () => static::getUrl('index'))
                ->isActiveWhen(fn () => $page instanceof EditWhiteLabelSettings),
            NavigationItem::make(__('filament-white-label::resource.sub_navigation.layout'))
                ->label(__('filament-white-label::resource.sub_navigation.layout'))
                ->icon('heroicon-o-rectangle-group')
                ->url(fn () => static::getUrl('layout'))
                ->isActiveWhen(fn () => $page instanceof EditLayoutSettings),
            NavigationItem::make(__('filament-white-label::resource.sub_navigation.advanced'))
                ->label(__('filament-white-label::resource.sub_navigation.advanced'))
                ->icon('heroicon-o-cog-6-tooth')
                ->url(fn () => static::getUrl('advanced'))
                ->isActiveWhen(fn () => $page instanceof EditAdvancedSettings),
        ];
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->schema([
                Section::make(__('filament-white-label::resource.sections.brand_identity'))->schema([
                    Grid::make(2)->schema([
                        TextInput::make('metadata.brand_name')
                            ->label(__('filament-white-label::resource.fields.brand_name.label'))
                            ->required()
                            ->maxLength(255)
                            ->placeholder(config('app.name')),

                        TextInput::make('metadata.brand_logo_height')
                            ->label(__('filament-white-label::resource.fields.logo_height.label'))
                            ->placeholder(__('filament-white-label::resource.fields.logo_height.placeholder'))
                            ->helperText(__('filament-white-label::resource.fields.logo_height.helper_text')),
                    ]),

                    Grid::make(2)->schema([
                        FileUpload::make('metadata.logo_path')
                            ->label(__('filament-white-label::resource.fields.logo_light.label'))
                            ->image()
                            ->imageResizeMode('contain')
                            ->directory(static::storageDirectory('logos'))
                            ->disk(config('filament-white-label.disk', 'public'))
                            ->maxSize(2048)
                            ->acceptedFileTypes(['image/png', 'image/jpeg', 'image/svg+xml', 'image/webp']),

                        FileUpload::make('metadata.dark_mode_logo_path')
                            ->label(__('filament-white-label::resource.fields.logo_dark.label'))
                            ->image()
                            ->imageResizeMode('contain')
                            ->directory(static::storageDirectory('logos'))
                            ->disk(config('filament-white-label.disk', 'public'))
                            ->maxSize(2048)
                            ->acceptedFileTypes(['image/png', 'image/jpeg', 'image/svg+xml', 'image/webp'])
                            ->helperText(__('filament-white-label::resource.fields.logo_dark.helper_text')),
                    ]),

                    Grid::make(2)->schema([
                        FileUpload::make('metadata.favicon_path')
                            ->label(__('filament-white-label::resource.fields.favicon.label'))
                            ->image()
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('1:1')
                            ->directory(static::storageDirectory('favicons'))
                            ->disk(config('filament-white-label.disk', 'public'))
                            ->maxSize(512)
                            ->acceptedFileTypes(['image/png', 'image/x-icon', 'image/svg+xml']),
                    ]),
                ])->columns(1),

                Section::make(__('filament-white-label::resource.sections.colors'))->schema([
                    Select::make('metadata.colors.primary')->label(__('filament-white-label::resource.fields.colors.primary.label'))
                        ->default('#3b82f6')
                        ->options(fn () => EditWhiteLabelSettings::paletteOptions())
                        ->searchable(),

                    Select::make('metadata.colors.secondary')->label(__('filament-white-label::resource.fields.colors.secondary.label'))
                        ->default('#64748b')
                        ->options(fn () => EditWhiteLabelSettings::paletteOptions())
                        ->searchable(),

                    Select::make('metadata.colors.danger')->label(__('filament-white-label::resource.fields.colors.danger.label'))
                        ->default('#ef4444')
                        ->options(fn () => EditWhiteLabelSettings::paletteOptions())
                        ->searchable(),

                    Select::make('metadata.colors.warning')->label(__('filament-white-label::resource.fields.colors.warning.label'))
                        ->default('#f59e0b')
                        ->options(fn () => EditWhiteLabelSettings::paletteOptions())
                        ->searchable(),

                    Select::make('metadata.colors.success')->label(__('filament-white-label::resource.fields.colors.success.label'))
                        ->default('#22c55e')
                        ->options(fn () => EditWhiteLabelSettings::paletteOptions())
                        ->searchable(),

                    Select::make('metadata.colors.info')->label(__('filament-white-label::resource.fields.colors.info.label'))
                        ->default('#3b82f6')
                        ->options(fn () => EditWhiteLabelSettings::paletteOptions())
                        ->searchable(),
                ])->columns(3),

                Section::make(__('filament-white-label::resource.sections.typography'))->schema([
                    Select::make('metadata.font_family')
                        ->label(__('filament-white-label::resource.fields.font_family.label'))
                        ->options(fn () => FontService::fontOptions())
                        ->searchable()
                        ->default('Inter'),
                ]),

                Section::make(__('filament-white-label::resource.sections.custom_css'))->schema([
                    Textarea::make('metadata.custom_css')
                        ->label(__('filament-white-label::resource.fields.custom_css.label'))
                        ->rows(10)
                        ->maxLength(config('filament-white-label.security.max_css_length', 50000))
                        ->helperText(__('filament-white-label::resource.fields.custom_css.helper_text'))
                        ->visible(fn () => ! config('filament-white-label.security.disable_custom_css', false)),
                ])->collapsed(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('metadata.logo_path')
                    ->label(__('filament-white-label::resource.table.columns.logo'))
                    ->circular()->size(40),
                TextColumn::make('metadata.brand_name')
                    ->label(__('filament-white-label::resource.table.columns.brand'))
                    ->searchable(),
                TextColumn::make('updated_at')
                    ->label(__('filament-white-label::resource.table.columns.updated'))
                    ->dateTime(),
            ])
            ->actions([
                EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => EditWhiteLabelSettings::route('/'),
            'layout' => EditLayoutSettings::route('/layout'),
            'advanced' => EditAdvancedSettings::route('/advanced'),
        ];
    }

    public static function resolveRecord(): WhiteLabelSettings
    {
        $tenant = Filament::getTenant();
        $panelId = Filament::getCurrentPanel()?->getId();

        $query = WhiteLabelSettings::query();

        if ($tenant) {
            $query->where('tenant_type', $tenant->getMorphClass())
                ->where('tenant_id', $tenant->getKey());
        } else {
            $query->whereNull('tenant_type')->whereNull('tenant_id');
        }

        $query->where(fn ($q) => $q->where('panel_id', $panelId)->orWhereNull('panel_id'))
            ->orderByRaw('panel_id IS NOT NULL DESC');

        $settings = $query->first();

        if (! $settings) {
            $settings = WhiteLabelSettings::create([
                'tenant_type' => $tenant?->getMorphClass(),
                'tenant_id' => $tenant?->getKey(),
                'panel_id' => $panelId,
                'metadata' => [
                    'brand_name' => $tenant?->name ?? config('app.name'),
                    'font_family' => config('filament-white-label.defaults.font_family', 'Inter'),
                    'colors' => WhiteLabel::defaultColors(),
                    'topbar' => config('filament-white-label.defaults.topbar', true),
                    'top_navigation' => config('filament-white-label.defaults.top_navigation', false),
                    'sidebar_collapsible_on_desktop' => config('filament-white-label.defaults.sidebar_collapsible_on_desktop', false),
                    'sidebar_fully_collapsible_on_desktop' => config('filament-white-label.defaults.sidebar_fully_collapsible_on_desktop', false),
                    'collapsible_navigation_groups' => config('filament-white-label.defaults.collapsible_navigation_groups', true),
                    'breadcrumbs' => config('filament-white-label.defaults.breadcrumbs', true),
                    'unsaved_changes_alerts' => config('filament-white-label.defaults.unsaved_changes_alerts', false),
                    'spa_mode' => config('filament-white-label.defaults.spa_mode', false),
                    'database_notifications' => config('filament-white-label.defaults.database_notifications', false),
                    'database_notifications_polling' => config('filament-white-label.defaults.database_notifications_polling', '30s'),
                    'border_radius' => config('filament-white-label.defaults.border_radius', 'default'),
                    'input_border_radius' => config('filament-white-label.defaults.input_border_radius'),
                    'badge_shape' => config('filament-white-label.defaults.badge_shape', 'default'),
                    'shadow_intensity' => config('filament-white-label.defaults.shadow_intensity', 'default'),
                    'container_width' => config('filament-white-label.defaults.container_width'),
                    'sidebar_width' => config('filament-white-label.defaults.sidebar_width'),
                    'heading_size' => config('filament-white-label.defaults.heading_size', 'default'),
                    'nav_item_spacing' => config('filament-white-label.defaults.nav_item_spacing', 'default'),
                    'font_scale' => config('filament-white-label.defaults.font_scale'),
                    'form_density' => config('filament-white-label.defaults.form_density', 'default'),
                    'table_row_density' => config('filament-white-label.defaults.table_row_density', 'default'),
                    'modal_size' => config('filament-white-label.defaults.modal_size', 'default'),
                    'transition_speed' => config('filament-white-label.defaults.transition_speed', 'default'),
                    'footer_text' => config('filament-white-label.defaults.footer_text'),
                    'footer_links' => config('filament-white-label.defaults.footer_links', []),
                ],
            ]);
        }

        return $settings;
    }

    public static function storageDirectory(string $type): string
    {
        $prefix = config('filament-white-label.storage_path_prefix', 'brand');
        $tenant = Filament::getTenant();

        if ($tenant) {
            return "{$prefix}/{$tenant->getMorphClass()}-{$tenant->getKey()}/{$type}";
        }

        return "{$prefix}/global/{$type}";
    }
}
