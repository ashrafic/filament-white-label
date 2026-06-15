<?php

declare(strict_types=1);

namespace FilamentWhiteLabel\Resources\WhiteLabelSettingsResource\Pages;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\EditRecord;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Colors\Color;
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
                            ->default(config('app.name'))
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
                            ->directory(WhiteLabelSettingsResource::storageDirectory('logos'))
                            ->disk(config('filament-white-label.disk', 'public'))
                            ->maxSize(2048)
                            ->acceptedFileTypes(['image/png', 'image/jpeg', 'image/svg+xml', 'image/webp']),

                        FileUpload::make('metadata.dark_mode_logo_path')
                            ->label('Logo (Dark)')
                            ->image()
                            ->imageResizeMode('contain')
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
                    Fieldset::make('Primary')->schema([
                        Select::make('_ui_primary_palette')
                            ->label('Palette')
                            ->options(fn () => static::paletteOptions())
                            ->native(true)
                            ->dehydrated(false)
                            ->live()
                            ->afterStateHydrated(function ($state, $set, $get) {
                                $hex = $get('metadata.colors.primary');
                                $palette = static::hexToPalette($hex);
                                if ($palette) {
                                    $set('_ui_primary_palette', $palette);
                                } elseif ($hex) {
                                    $set('_ui_primary_palette', 'custom');
                                }
                            })
                            ->afterStateUpdated(function ($state, $set) {
                                if ($state && $state !== 'custom') {
                                    $hex = static::paletteHex($state);
                                    if ($hex) {
                                        $set('metadata.colors.primary', $hex);
                                    }
                                }
                            }),
                        ColorPicker::make('metadata.colors.primary')
                            ->label('Hex')
                            ->live()
                            ->afterStateUpdated(function ($state, $set) {
                                $palette = static::hexToPalette($state);
                                $set('_ui_primary_palette', $palette ?? 'custom');
                            }),
                    ])->columns(2),
                    Fieldset::make('Secondary')->schema([
                        Select::make('_ui_secondary_palette')
                            ->label('Palette')
                            ->options(fn () => static::paletteOptions())
                            ->native(true)
                            ->dehydrated(false)
                            ->live()
                            ->afterStateHydrated(function ($state, $set, $get) {
                                $hex = $get('metadata.colors.secondary');
                                $palette = static::hexToPalette($hex);
                                if ($palette) {
                                    $set('_ui_secondary_palette', $palette);
                                } elseif ($hex) {
                                    $set('_ui_secondary_palette', 'custom');
                                }
                            })
                            ->afterStateUpdated(function ($state, $set) {
                                if ($state && $state !== 'custom') {
                                    $hex = static::paletteHex($state);
                                    if ($hex) {
                                        $set('metadata.colors.secondary', $hex);
                                    }
                                }
                            }),
                        ColorPicker::make('metadata.colors.secondary')
                            ->label('Hex')
                            ->live()
                            ->afterStateUpdated(function ($state, $set) {
                                $palette = static::hexToPalette($state);
                                $set('_ui_secondary_palette', $palette ?? 'custom');
                            }),
                    ])->columns(2),
                    Fieldset::make('Danger')->schema([
                        Select::make('_ui_danger_palette')
                            ->label('Palette')
                            ->options(fn () => static::paletteOptions())
                            ->native(true)
                            ->dehydrated(false)
                            ->live()
                            ->afterStateHydrated(function ($state, $set, $get) {
                                $hex = $get('metadata.colors.danger');
                                $palette = static::hexToPalette($hex);
                                if ($palette) {
                                    $set('_ui_danger_palette', $palette);
                                } elseif ($hex) {
                                    $set('_ui_danger_palette', 'custom');
                                }
                            })
                            ->afterStateUpdated(function ($state, $set) {
                                if ($state && $state !== 'custom') {
                                    $hex = static::paletteHex($state);
                                    if ($hex) {
                                        $set('metadata.colors.danger', $hex);
                                    }
                                }
                            }),
                        ColorPicker::make('metadata.colors.danger')
                            ->label('Hex')
                            ->live()
                            ->afterStateUpdated(function ($state, $set) {
                                $palette = static::hexToPalette($state);
                                $set('_ui_danger_palette', $palette ?? 'custom');
                            }),
                    ])->columns(2),
                    Fieldset::make('Warning')->schema([
                        Select::make('_ui_warning_palette')
                            ->label('Palette')
                            ->options(fn () => static::paletteOptions())
                            ->native(true)
                            ->dehydrated(false)
                            ->live()
                            ->afterStateHydrated(function ($state, $set, $get) {
                                $hex = $get('metadata.colors.warning');
                                $palette = static::hexToPalette($hex);
                                if ($palette) {
                                    $set('_ui_warning_palette', $palette);
                                } elseif ($hex) {
                                    $set('_ui_warning_palette', 'custom');
                                }
                            })
                            ->afterStateUpdated(function ($state, $set) {
                                if ($state && $state !== 'custom') {
                                    $hex = static::paletteHex($state);
                                    if ($hex) {
                                        $set('metadata.colors.warning', $hex);
                                    }
                                }
                            }),
                        ColorPicker::make('metadata.colors.warning')
                            ->label('Hex')
                            ->live()
                            ->afterStateUpdated(function ($state, $set) {
                                $palette = static::hexToPalette($state);
                                $set('_ui_warning_palette', $palette ?? 'custom');
                            }),
                    ])->columns(2),
                    Fieldset::make('Success')->schema([
                        Select::make('_ui_success_palette')
                            ->label('Palette')
                            ->options(fn () => static::paletteOptions())
                            ->native(true)
                            ->dehydrated(false)
                            ->live()
                            ->afterStateHydrated(function ($state, $set, $get) {
                                $hex = $get('metadata.colors.success');
                                $palette = static::hexToPalette($hex);
                                if ($palette) {
                                    $set('_ui_success_palette', $palette);
                                } elseif ($hex) {
                                    $set('_ui_success_palette', 'custom');
                                }
                            })
                            ->afterStateUpdated(function ($state, $set) {
                                if ($state && $state !== 'custom') {
                                    $hex = static::paletteHex($state);
                                    if ($hex) {
                                        $set('metadata.colors.success', $hex);
                                    }
                                }
                            }),
                        ColorPicker::make('metadata.colors.success')
                            ->label('Hex')
                            ->live()
                            ->afterStateUpdated(function ($state, $set) {
                                $palette = static::hexToPalette($state);
                                $set('_ui_success_palette', $palette ?? 'custom');
                            }),
                    ])->columns(2),
                    Fieldset::make('Info')->schema([
                        Select::make('_ui_info_palette')
                            ->label('Palette')
                            ->options(fn () => static::paletteOptions())
                            ->native(true)
                            ->dehydrated(false)
                            ->live()
                            ->afterStateHydrated(function ($state, $set, $get) {
                                $hex = $get('metadata.colors.info');
                                $palette = static::hexToPalette($hex);
                                if ($palette) {
                                    $set('_ui_info_palette', $palette);
                                } elseif ($hex) {
                                    $set('_ui_info_palette', 'custom');
                                }
                            })
                            ->afterStateUpdated(function ($state, $set) {
                                if ($state && $state !== 'custom') {
                                    $hex = static::paletteHex($state);
                                    if ($hex) {
                                        $set('metadata.colors.info', $hex);
                                    }
                                }
                            }),
                        ColorPicker::make('metadata.colors.info')
                            ->label('Hex')
                            ->live()
                            ->afterStateUpdated(function ($state, $set) {
                                $palette = static::hexToPalette($state);
                                $set('_ui_info_palette', $palette ?? 'custom');
                            }),
                    ])->columns(2),
                ])->columns(2),

                Section::make('Typography')->schema([
                    Select::make('metadata.font_family')
                        ->label('Font Family')
                        ->options(fn () => FontService::fontOptions())
                        ->searchable()
                        ->default('Inter'),
                ]),

                Section::make('Styling')->schema([
                    Select::make('metadata.border_radius')
                        ->label('Border Radius')
                        ->default('default')
                        ->options([
                            'default' => 'Default',
                            'none' => 'None',
                            'small' => 'Small',
                            'medium' => 'Medium',
                            'large' => 'Large',
                            'pill' => 'Pill',
                        ])
                        ->helperText('Rounded corners on buttons, cards, inputs, modals, and dropdowns.'),

                    Select::make('metadata.input_border_radius')
                        ->label('Input Border Radius')
                        ->default(null)
                        ->options([
                            null => 'Inherit',
                            'default' => 'Default',
                            'none' => 'None',
                            'small' => 'Small',
                            'medium' => 'Medium',
                            'large' => 'Large',
                            'pill' => 'Pill',
                        ])
                        ->helperText('Override border radius specifically for text inputs and selects.'),

                    Select::make('metadata.shadow_intensity')
                        ->label('Shadow Intensity')
                        ->default('default')
                        ->options([
                            'default' => 'Default',
                            'none' => 'None',
                            'subtle' => 'Subtle',
                            'pronounced' => 'Pronounced',
                        ])
                        ->helperText('Box shadow on cards and dropdown panels.'),

                    Select::make('metadata.badge_shape')
                        ->label('Badge Shape')
                        ->default('default')
                        ->options([
                            'default' => 'Default',
                            'sharp' => 'Sharp',
                            'rounded' => 'Rounded',
                            'pill' => 'Pill',
                        ])
                        ->helperText('Border radius and padding for badges.'),
                ])->columns(2),

                Section::make('Custom CSS')->schema([
                    Textarea::make('metadata.custom_css')
                        ->label('Custom CSS')
                        ->rows(10)
                        ->maxLength(config('filament-white-label.security.max_css_length', 50000))
                        ->helperText('Custom CSS will be injected into your panel. <script> tags are automatically removed for security.')
                        ->visible(fn () => ! config('filament-white-label.security.disable_custom_css', false)),
                ])->collapsed(),
            ]);
    }

    public static function paletteOptions(): array
    {
        $options = ['custom' => 'Custom hex...'];

        foreach (Color::all() as $name => $shades) {
            $hex = Color::convertToHex($shades[500]);
            $options[$name] = ucfirst($name).' · '.$hex;
        }

        return $options;
    }

    public static function colorHint(?string $hex): string
    {
        if (blank($hex)) {
            return '';
        }

        return sprintf(
            '<span style="display:inline-block;width:1rem;height:1rem;border-radius:50%%;background-color:%s;vertical-align:middle;border:1px solid rgba(0,0,0,.1)"></span>',
            e($hex),
        );
    }

    public static function paletteHex(?string $name): ?string
    {
        if (! $name) {
            return null;
        }

        $palettes = Color::all();

        if (isset($palettes[$name])) {
            return Color::convertToHex($palettes[$name][500]);
        }

        return null;
    }

    public static function hexToPalette(?string $hex): ?string
    {
        if (! $hex) {
            return null;
        }

        foreach (Color::all() as $name => $shades) {
            if (Color::convertToHex($shades[500]) === $hex) {
                return $name;
            }
        }

        return null;
    }

    public static function paletteData(): array
    {
        $data = [];

        foreach (Color::all() as $name => $shades) {
            $data[$name] = [
                'name' => ucfirst($name),
                'hex' => Color::convertToHex($shades[500]),
            ];
        }

        return $data;
    }
}
