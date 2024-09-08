<?php
namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;

class FidelityCardService
{

public function generatePdf(string $view, array $data, ?string $filePath = null): string
{
    // Charger la vue avec les données et générer le PDF
    $pdf = Pdf::loadView($view, $data);

    // Si un chemin de fichier est fourni, sauvegarder le PDF
    if ($filePath) {
        $pdf->save($filePath);
    }

    // Retourner le contenu du PDF en chaîne de caractères
    return $pdf->output();
}


    public function generateFidelityCard( $client): string
    {
        $qrCodePath = $this->generateQRCode($client);
        $data = [
            'client' => $client,
            'qrCodePath' => $qrCodePath,
            'photoPath' => $client->photo_path,
        ];
        $client->qrcode=$qrCodePath;
        $client->save();
        // dd($client);
        $filePath = storage_path('client_' . $client->id . '.pdf');
        $this->generatePdf('fidelity_card', $data, $filePath);

        return $filePath;
    }

    public function generateQRCode($client): string {
        // Générer les données du QR code
        $qrCodeData = json_encode([
            'client_id' => $client->id,
            'nom' => $client->user->nom,
            'prenom' => $client->user->prenom,
            'telephone' => $client->telephone,
            'photo' => $client->photo_path,
        ]);

        // Utiliser le service pour générer le QR code sans stockage
        $qrCodeService = app(QRCodeService::class);
        return    $qrCodeImage = $qrCodeService->generateQRCode($qrCodeData);

        // Convertir l'image en Base64


    }


}
