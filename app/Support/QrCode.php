<?php

namespace App\Support;

use InvalidArgumentException;

class QrCode
{
    private const BORDER_MODULES = 4;

    public static function svg(string $data): string
    {
        $qr = self::encode($data);
        $modules = $qr->getModuleCount();
        $size = $modules + (self::BORDER_MODULES * 2);
        $path = '';

        for ($row = 0; $row < $modules; $row++) {
            for ($column = 0; $column < $modules; $column++) {
                if (! $qr->isDark($row, $column)) {
                    continue;
                }

                $x = $column + self::BORDER_MODULES;
                $y = $row + self::BORDER_MODULES;
                $path .= "M{$x} {$y}h1v1h-1z";
            }
        }

        return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 '.$size.' '.$size.'" role="img" aria-label="Kod QR pengesahan dua faktor" shape-rendering="crispEdges" style="display:block;width:100%;height:auto">'
            .'<rect width="'.$size.'" height="'.$size.'" fill="#ffffff"/>'
            .'<path fill="#000000" d="'.$path.'"/>'
            .'</svg>';
    }

    private static function encode(string $data): \QRCode
    {
        self::loadGenerator();

        if ($data === '') {
            throw new InvalidArgumentException('QR data cannot be empty.');
        }

        $levels = [QR_ERROR_CORRECT_LEVEL_M, QR_ERROR_CORRECT_LEVEL_L];

        foreach ($levels as $level) {
            $qr = new \QRCode;
            $qr->setErrorCorrectLevel($level);
            $qr->addData($data);
            $segment = $qr->getData(0);
            $maxVersion = count(\QRUtil::$QR_MAX_LENGTH);

            for ($version = 1; $version <= $maxVersion; $version++) {
                if ($segment->getLength() > \QRUtil::getMaxLength($version, $segment->getMode(), $level)) {
                    continue;
                }

                $qr->setTypeNumber($version);
                $qr->make();

                return $qr;
            }
        }

        throw new InvalidArgumentException('Data is too long to encode as a QR code.');
    }

    private static function loadGenerator(): void
    {
        require_once __DIR__.'/Qr/qrcode.php';
    }
}
