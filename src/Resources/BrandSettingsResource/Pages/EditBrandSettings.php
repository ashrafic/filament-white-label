<?php

declare(strict_types=1);

namespace FilamentWhiteLabel\Resources\BrandSettingsResource\Pages;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\EditRecord;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use FilamentWhiteLabel\Fonts\FontService;
use FilamentWhiteLabel\Resources\BrandSettingsResource;

class EditBrandSettings extends EditRecord
{
    protected static string $resource = BrandSettingsResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function mount(int | string | null $record = null): void
    {
        $this->record = BrandSettingsResource::resolveBrandSettingsRecord();

        $this->authorizeAccess();

        $this->fillForm();

        $this->previousUrl = url()->previous();
    }

    public function form(Schema $schema): Schema
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
                        ->directory(BrandSettingsResource::storageDirectory('logos'))
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
                        ->directory(BrandSettingsResource::storageDirectory('favicons'))
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
}
