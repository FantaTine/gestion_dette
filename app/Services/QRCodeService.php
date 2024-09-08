<?php

namespace App\Services;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;

class QRCodeService
{


public function generateQRCode(string $qrContent): string
{
    // Générer le QR code avec endroid/qr-code
    $qrCode = Builder::create()
        ->writer(new PngWriter())
        ->data($qrContent)
        ->size(100)   // Taille du QR code
        ->margin(10)  // Marge du QR code
        ->build();

    // Récupérer l'image en tant que données binaires
    $qrCodeData = $qrCode->getString();

    // Convertir en Base64
    $base64QRCode = base64_encode($qrCodeData);

    return $base64QRCode;
}

}
