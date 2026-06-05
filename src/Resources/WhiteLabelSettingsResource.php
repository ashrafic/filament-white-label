<?php

declare(strict_types=1);

namespace FilamentWhiteLabel\Resources;

use BackedEnum;
use Filament\Facades\Filament;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Page;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use FilamentWhiteLabel\Fonts\FontService;
use FilamentWhiteLabel\Models\WhiteLabelSettings;
use FilamentWhiteLabel\Resources\WhiteLabelSettingsResource\Pages\EditAdvancedSettings;
use FilamentWhiteLabel\Resources\WhiteLabelSettingsResource\Pages\EditWhiteLabelSettings;
use FilamentWhiteLabel\Resources\WhiteLabelSettingsResource\Pages\EditLayoutSettings;

class WhiteLabelSettingsResource extends Resource
{
    protected static ?string $model = WhiteLabelSettings::class;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-swatch';

    protected static ?string $label = 'Brand Settings';

    protected static ?string $pluralLabel = 'Brand Settings';

    public static function getNavigationGroup(): ?string
    {
        return config('filament-white-label.ui.navigation_group', 'White Label');
    }

    public static function getNavigationSort(): ?int
    {
        return config('filament-white-label.ui.navigation_sort', 10);
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            EditWhiteLabelSettings::class,
            EditLayoutSettings::class,
            EditAdvancedSettings::class,
        ]);
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->schema([
                Section::make('Brand Identity')->schema([
                    TextInput::make('metadata.brand_name')
                        ->label('Brand Name')
                        ->required()
                        ->maxLength(255)
                        ->placeholder(config('app.name')),

                    FileUpload::make('metadata.logo_path')
                        ->label('Logo')
                        ->image()
                        ->imageResizeMode('contain')
                        ->imageCropAspectRatio('3:1')
                        ->directory(static::storageDirectory('logos'))
                        ->disk(config('filament-white-label.disk', 'public'))
                        ->maxSize(2048)
                        ->acceptedFileTypes(['image/png', 'image/jpeg', 'image/svg+xml', 'image/webp']),

                    TextInput::make('metadata.brand_logo_height')
                        ->label('Logo Height')
                        ->placeholder('2.5rem')
                        ->helperText('CSS height value. Leave empty for Filament default.'),

                    FileUpload::make('metadata.favicon_path')
                        ->label('Favicon')
                        ->image()
                        ->imageResizeMode('cover')
                        ->imageCropAspectRatio('1:1')
                        ->directory(static::storageDirectory('favicons'))
                        ->disk(config('filament-white-label.disk', 'public'))
                        ->maxSize(512)
                        ->acceptedFileTypes(['image/png', 'image/x-icon', 'image/svg+xml']),
                ])->columns(2),

                Section::make('Colors')->schema([
                    ColorPicker::make('metadata.colors.primary')->label('Primary')->default('#3b82f6'),
                    ColorPicker::make('metadata.colors.secondary')->label('Secondary')->default('#64748b'),
                    ColorPicker::make('metadata.colors.danger')->label('Danger')->default('#ef4444'),
                    ColorPicker::make('metadata.colors.warning')->label('Warning')->default('#f59e0b'),
                    ColorPicker::make('metadata.colors.success')->label('Success')->default('#22c55e'),
                    ColorPicker::make('metadata.colors.info')->label('Info')->default('#3b82f6'),
                ])->columns(3),

                Section::make('Typography')->schema([
                    Select::make('metadata.font_family')
                        ->label('Font Family')
                        ->options(fn () => FontService::fontOptions())
                        ->searchable()
                        ->default('Inter'),
                ]),

                Section::make('Custom CSS')->schema([
                    Textarea::make('metadata.custom_css')
                        ->label('Custom CSS')
                        ->rows(10)
                        ->maxLength(config('filament-white-label.security.max_css_length', 50000))
                        ->helperText('Custom CSS will be injected into your panel. <script> tags are automatically removed for security.')
                        ->visible(fn () => ! config('filament-white-label.security.disable_custom_css', false)),
                ])->collapsed(),

                Section::make('Email Branding')->schema([
                    TextInput::make('metadata.email_from_address')
                        ->label('From Address')
                        ->email()
                        ->placeholder(config('mail.from.address')),

                    TextInput::make('metadata.email_from_name')
                        ->label('From Name')
                        ->maxLength(255)
                        ->placeholder(config('mail.from.name')),
                ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('metadata.logo_path')->label('Logo')->circular()->size(40),
                TextColumn::make('metadata.brand_name')->label('Brand')->searchable(),
                TextColumn::make('metadata.email_from_address')->label('Email From'),
                TextColumn::make('updated_at')->label('Last Updated')->dateTime(),
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
                    'colors' => config('filament-white-label.defaults.colors'),
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
