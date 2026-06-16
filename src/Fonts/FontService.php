<?php

declare(strict_types=1);

namespace FilamentWhiteLabel\Fonts;

class FontService
{
    public static function fontOptions(): array
    {
        return [
            'Inter' => 'Inter (Default)',
            'Roboto' => 'Roboto',
            'Open Sans' => 'Open Sans',
            'Lato' => 'Lato',
            'Montserrat' => 'Montserrat',
            'Poppins' => 'Poppins',
            'Nunito' => 'Nunito',
            'Raleway' => 'Raleway',
            'Ubuntu' => 'Ubuntu',
            'Source Sans Pro' => 'Source Sans Pro',
            'Merriweather' => 'Merriweather',
            'Playfair Display' => 'Playfair Display',
            'DM Sans' => 'DM Sans',
            'Space Grotesk' => 'Space Grotesk',
            'JetBrains Mono' => 'JetBrains Mono',
            'Fira Sans' => 'Fira Sans',
            'Fira Code' => 'Fira Code',
            'IBM Plex Sans' => 'IBM Plex Sans',
            'Work Sans' => 'Work Sans',
            'Quicksand' => 'Quicksand',
            'Rubik' => 'Rubik',
            'Barlow' => 'Barlow',
            'Titillium Web' => 'Titillium Web',
            'Oswald' => 'Oswald',
            'Josefin Sans' => 'Josefin Sans',
            'Cabin' => 'Cabin',
            'PT Sans' => 'PT Sans',
            'PT Serif' => 'PT Serif',
            'Noto Sans' => 'Noto Sans',
            'Noto Serif' => 'Noto Serif',
            'Libre Franklin' => 'Libre Franklin',
            'Libre Baskerville' => 'Libre Baskerville',
            'Archivo' => 'Archivo',
            'Plus Jakarta Sans' => 'Plus Jakarta Sans',
            'Outfit' => 'Outfit',
            'Manrope' => 'Manrope',
            'Lexend' => 'Lexend',
            'Sora' => 'Sora',
            'Epilogue' => 'Epilogue',
            'Public Sans' => 'Public Sans',
            'Commissioner' => 'Commissioner',
            'Urbanist' => 'Urbanist',
            'Inter Tight' => 'Inter Tight',
            'Geist' => 'Geist',
            'Geist Mono' => 'Geist Mono',
            'Instrument Sans' => 'Instrument Sans',
            'Instrument Serif' => 'Instrument Serif',
            'Onest' => 'Onest',
            'Afacad' => 'Afacad',
        ];
    }

    public static function linkTag(string $family, array $weights = [400, 500, 600, 700]): string
    {
        if ($family === 'Inter') {
            return '';
        }

        $familyParam = urlencode($family);
        $weightsParam = implode(';', array_map(fn (int $w) => "0,{$w}", $weights));

        $href = "https://fonts.googleapis.com/css2?family={$familyParam}:wght@{$weightsParam}&display=swap";

        return '<link rel="preconnect" href="https://fonts.googleapis.com">'
            .'<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>'
            .'<link href="'.e($href).'" rel="stylesheet">';
    }
}
