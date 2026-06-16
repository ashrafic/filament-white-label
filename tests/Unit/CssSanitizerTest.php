<?php

declare(strict_types=1);

use FilamentWhiteLabel\Security\CssSanitizer;

it('strips script tags with content', function () {
    $css = "body { color: red; } <script>alert('xss')</script> .foo { margin: 0; }";

    $result = CssSanitizer::sanitize($css);

    expect($result)->not->toContain('script');
    expect($result)->not->toContain('alert');
    expect($result)->toContain('body { color: red; }');
    expect($result)->toContain('.foo { margin: 0; }');
});

it('strips multiline script tags', function () {
    $css = "body { color: red; }\n<script>\nalert('xss')\n</script>\n.foo { }";

    $result = CssSanitizer::sanitize($css);

    expect($result)->not->toContain('script');
    expect($result)->not->toContain('alert');
});

it('strips mixed-case script tags', function () {
    $css = "body { } <SCRIPT>alert('xss')</SCRIPT> .foo { }";

    $result = CssSanitizer::sanitize($css);

    expect($result)->not->toContain('SCRIPT');
    expect($result)->not->toContain('alert');
});

it('strips javascript protocol in url()', function () {
    $css = 'body { background: url(javascript:alert("xss")); }';

    $result = CssSanitizer::sanitize($css);

    expect($result)->not->toContain('javascript');
    expect($result)->toContain('invalid-protocol-removed');
});

it('strips expression() calls', function () {
    $css = 'body { width: expression(alert("xss")); }';

    $result = CssSanitizer::sanitize($css);

    expect($result)->not->toContain('expression(');
    expect($result)->toContain('(removed-expression');
});

it('preserves valid CSS', function () {
    $css = 'body { color: red; background: #fff; } .btn { padding: 10px; }';

    $result = CssSanitizer::sanitize($css);

    expect($result)->toBe($css);
});

it('handles empty string', function () {
    $result = CssSanitizer::sanitize('');

    expect($result)->toBe('');
});

it('detects dangerous content with isDangerous', function () {
    expect(CssSanitizer::isDangerous('<script>alert(1)</script> body {}'))->toBeTrue();
    expect(CssSanitizer::isDangerous('body {}'))->toBeFalse();
});
