<?php

declare(strict_types=1);

namespace FilamentWhiteLabel\Resources;

use Filament\Facades\Filament;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use FilamentWhiteLabel\Fonts\FontService;
use FilamentWhiteLabel\Models\BrandSettings;
use FilamentWhiteLabel\Resources\BrandSettingsResource\Pages\EditBrandSettings;

class BrandSettingsResource extends Resource
{
    protected static ?string $model = BrandSettings::class;

    protected static ?string $navigationIcon = 'heroicon-o-paint-brush';

    protected static ?string $label = 'Brand Settings';

    protected static ?string $pluralLabel = 'Brand Settings';

    public static function getNavigationGroup(): ?string
    {
        return config('filament-white-label.ui.navigation_group', 'Settings');
    }

    public static function getNavigationSort(): ?int
    {
        return config('filament-white-label.ui.navigation_sort', 10);
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Brand Identity')->schema([
                TextInput::make('brand_name')
                    ->label('Brand Name')
                    ->required()
                    ->maxLength(255)
                    ->placeholder(config('app.name')),

                FileUpload::make('logo_path')
                    ->label('Logo')
                    ->image()
                    ->imageResizeMode('contain')
                    ->imageCropAspectRatio('3:1')
                    ->directory(static::storageDirectory('logos'))
                    ->disk(config('filament-white-label.disk', 'public'))
                    ->maxSize(2048)
                    ->acceptedFileTypes(['image/png', 'image/jpeg', 'image/svg+xml', 'image/webp']),

                FileUpload::make('favicon_path')
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
                ColorPicker::make('colors.primary')->label('Primary')->default('#3b82f6'),
                ColorPicker::make('colors.secondary')->label('Secondary')->default('#64748b'),
                ColorPicker::make('colors.danger')->label('Danger')->default('#ef4444'),
                ColorPicker::make('colors.warning')->label('Warning')->default('#f59e0b'),
                ColorPicker::make('colors.success')->label('Success')->default('#22c55e'),
                ColorPicker::make('colors.info')->label('Info')->default('#3b82f6'),
            ])->columns(3),

            Section::make('Typography')->schema([
                Select::make('font_family')
                    ->label('Font Family')
                    ->options(fn () => FontService::fontOptions())
                    ->searchable()
                    ->default('Inter'),
            ]),

            Section::make('Custom CSS')->schema([
                Textarea::make('custom_css')
                    ->label('Custom CSS')
                    ->rows(10)
                    ->maxLength(config('filament-white-label.security.max_css_length', 50000))
                    ->helperText('Custom CSS will be injected into your panel. <script> tags are automatically removed for security.')
                    ->visible(fn () => ! config('filament-white-label.security.disable_custom_css', false)),
            ])->collapsed(),

            Section::make('Email Branding')->schema([
                TextInput::make('email_from_address')
                    ->label('From Address')
                    ->email()
                    ->placeholder(config('mail.from.address')),

                TextInput::make('email_from_name')
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
                ImageColumn::make('logo_path')->label('Logo')->circular()->size(40),
                TextColumn::make('brand_name')->label('Brand')->searchable(),
                TextColumn::make('email_from_address')->label('Email From'),
                TextColumn::make('updated_at')->label('Last Updated')->dateTime(),
            ])
            ->actions([
                EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => EditBrandSettings::route('/'),
        ];
    }

    protected static function storageDirectory(string $type): string
    {
        $prefix = config('filament-white-label.storage_path_prefix', 'brand');
        $tenant = Filament::getTenant();

        if ($tenant) {
            return "{$prefix}/{$tenant->getMorphClass()}-{$tenant->getKey()}/{$type}";
        }

        return "{$prefix}/global/{$type}";
    }
}