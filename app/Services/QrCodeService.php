<?php

namespace App\Services;

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

class QrCodeService
{
    /**
     * Generate a QR code for DomPDF.
     * Content format required: {id_zamowienia}|{id_produktu}
     *
     * @param int|string $orderId
     * @param int|string $productId
     * @return string Data URI (data:image/svg+xml;base64,...)
     */
    public function generateOrderItemQr($orderId, $productId): string
    {
        $code = "{$orderId}|{$productId}";

        $options = new QROptions([
            'outputBase64' => true,
            'addQuietzone' => true,
        ]);

        return (new QRCode($options))->render($code);
    }
}
