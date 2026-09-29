<?php

namespace App\Services;

use chillerlan\QRCode\Common\EccLevel;
use chillerlan\QRCode\Output\QRMarkupSVG;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

class QrCodeService
{
    /**
     * Render SVG string for given data/URL.
     */
    public function renderSvg(string $data): string
    {
        $options = new QROptions([
            'outputInterface' => QRMarkupSVG::class,
            'eccLevel' => EccLevel::M,
            'addQuietzone' => true,
            'svgAddXmlHeader' => false,
        ]);

        return (new QRCode($options))->render($data);
    }

    /**
     * Render base64 data URI image for given data/URL.
     */
    public function renderDataUri(string $data): string
    {
        return (new QRCode)->render($data);
    }
}
