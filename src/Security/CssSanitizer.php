<?php

declare(strict_types=1);

namespace FilamentWhiteLabel\Security;

class CssSanitizer
{
    public static function sanitize(string $css): string
    {
        $css = preg_replace(
            '/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/is',
            '',
            $css
        );

        $css = preg_replace('/<script\b[^>]*>/is', '', $css);

        $css = preg_replace(
            '/url\s*\(\s*["\']?\s*javascript\s*:/is',
            'url(invalid-protocol-removed:',
            $css
        );

        $css = preg_replace('/expression\s*\(/is', '(removed-expression', $css);

        return trim($css);
    }

    public static function isDangerous(string $css): bool
    {
        return (bool) preg_match(
            '/<script|javascript\s*:|expression\s*\(/is',
            $css
        );
    }
}