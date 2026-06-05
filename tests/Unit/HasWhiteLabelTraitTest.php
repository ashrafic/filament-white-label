<?php

declare(strict_types=1);

use FilamentWhiteLabel\Concerns\HasWhiteLabel;

test('all closure methods return Closure instances', function () {
    $trait = new class {
        use HasWhiteLabel;
    };

    $methods = [
        'whiteLabelBrandName',
        'whiteLabelLogo',
        'whiteLabelBrandLogoHeight',
        'whiteLabelDarkModeBrandLogo',
        'whiteLabelFavicon',
        'whiteLabelColors',
        'whiteLabelFontFamily',
        'whiteLabelHeadHook',
        'whiteLabelTopbar',
        'whiteLabelTopNavigation',
        'whiteLabelSidebarCollapsibleOnDesktop',
        'whiteLabelSidebarFullyCollapsibleOnDesktop',
        'whiteLabelCollapsibleNavigationGroups',
        'whiteLabelBreadcrumbs',
        'whiteLabelUnsavedChangesAlerts',
        'whiteLabelSpaMode',
        'whiteLabelDatabaseNotifications',
        'whiteLabelDatabaseNotificationsPolling',
    ];

    foreach ($methods as $method) {
        expect($trait->{$method}())->toBeInstanceOf(Closure::class);
    }
});
