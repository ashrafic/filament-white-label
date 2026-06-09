<?php

declare(strict_types=1);

namespace FilamentWhiteLabel\Resources\WhiteLabelSettingsResource\Pages;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\EditRecord;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use FilamentWhiteLabel\Fonts\FontService;
use FilamentWhiteLabel\Resources\WhiteLabelSettingsResource;
use Illuminate\Database\Eloquent\Model;

class EditWhiteLabelSettings extends EditRecord
{
    protected static string $resource = WhiteLabelSettingsResource::class;

    protected static ?string $title = 'Brand';

    protected static ?string $navigationLabel = 'Brand';

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['metadata'] = array_merge(
            $this->record->metadata ?? [],
            $data['metadata'] ?? [],
        );

        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $record = parent::handleRecordUpdate($record, $data);
        $this->js('setTimeout(() => window.location.reload(), 250)');

        return $record;
    }

    public function mount(int|string|null $record = null): void
    {
        $this->record = WhiteLabelSettingsResource::resolveRecord();

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
                    Grid::make(2)->schema([
                        TextInput::make('metadata.brand_name')
                            ->label('Brand Name')
                            ->required()
                            ->maxLength(255)
                            ->placeholder(config('app.name')),

                        TextInput::make('metadata.brand_logo_height')
                            ->label('Logo Height')
                            ->placeholder('2.5rem')
                            ->helperText('CSS height value. Leave empty for Filament default.'),
                    ]),

                    Grid::make(2)->schema([
                        FileUpload::make('metadata.logo_path')
                            ->label('Logo (Light)')
                            ->image()
                            ->imageResizeMode('contain')
                            ->imageCropAspectRatio('3:1')
                            ->directory(WhiteLabelSettingsResource::storageDirectory('logos'))
                            ->disk(config('filament-white-label.disk', 'public'))
                            ->maxSize(2048)
                            ->acceptedFileTypes(['image/png', 'image/jpeg', 'image/svg+xml', 'image/webp']),

                        FileUpload::make('metadata.dark_mode_logo_path')
                            ->label('Logo (Dark)')
                            ->image()
                            ->imageResizeMode('contain')
                            ->imageCropAspectRatio('3:1')
                            ->directory(WhiteLabelSettingsResource::storageDirectory('logos'))
                            ->disk(config('filament-white-label.disk', 'public'))
                            ->maxSize(2048)
                            ->acceptedFileTypes(['image/png', 'image/jpeg', 'image/svg+xml', 'image/webp'])
                            ->helperText('Falls back to light logo if not set.'),
                    ]),

                    Grid::make(2)->schema([
                        FileUpload::make('metadata.favicon_path')
                            ->label('Favicon')
                            ->image()
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('1:1')
                            ->directory(WhiteLabelSettingsResource::storageDirectory('favicons'))
                            ->disk(config('filament-white-label.disk', 'public'))
                            ->maxSize(512)
                            ->acceptedFileTypes(['image/png', 'image/x-icon', 'image/svg+xml']),
                    ]),
                ])->columns(1),

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
