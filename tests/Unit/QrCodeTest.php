<?php

use App\Support\QrCode;
use App\Support\Totp;

it('renders a provisioning uri as an svg qr code', function () {
    $uri = Totp::provisioningUri(
        'V4UV5URVGXNYXNPLQU43SWX337MNPSXH',
        'sainal@aadk.gov.my',
        'SPEKTRA',
    );

    $svg = QrCode::svg($uri);

    expect($svg)
        ->toStartWith('<svg')
        ->toContain('aria-label="Kod QR pengesahan dua faktor"')
        ->toContain('fill="#ffffff"')
        ->toContain('M4 4h1v1h-1z')
        ->toContain('M10 4h1v1h-1z')
        ->not->toContain($uri)
        ->not->toContain('<script');
});

it('rejects data that does not fit in a qr code', function () {
    QrCode::svg(str_repeat('A', 400));
})->throws(InvalidArgumentException::class);
