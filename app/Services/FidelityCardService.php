<?php
namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;

class FidelityCardService
{
    public function generatePdf(string $view, array $data, string $filePath)
    {
        $pdf = Pdf::loadView($view, $data);
        $pdf->save($filePath);
    }

    public function generateFidelityCard($client): string
    {
        $qrCodePath = $this->generateQRCode($client);
        $data = [
            'client' => $client,
            'qrCodePath' => $qrCodePath,
            'photoPath' => $client->photo_path,
        ];

        $filePath = storage_path('app/public/fidelity_cards/client_' . $client->id . '.pdf');
        $this->generatePdf('fidelity_card', $data, $filePath);

        return $filePath;
    }

    public function generateQRCode(Client $client): string
    {
    $qrCodePath = 'fidelity_cards/qrcode_client_' . $client->id . '.png';
    $qrCodeService = app(QRCodeService::class);
    $qrCodeService->generateQRCode(
        json_encode([
            'client_id' => $client->id,
            'nom' => $client->user->nom,
            'prenom' => $client->user->prenom,
            'telephone' => $client->telephone,
            'photo' => $client->photo_path,
        ]),
        $qrCodePath
    );

    return $qrCodePath;
    }

}
