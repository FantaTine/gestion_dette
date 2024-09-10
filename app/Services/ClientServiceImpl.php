<?php

namespace App\Services;

use App\Models\Client;
use App\Models\User;
use App\Repositories\ClientRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Cloudinary\Cloudinary;
use Illuminate\Support\Facades\Mail;
use App\Mail\FidelityCardMail;
use App\Jobs\UploadToCloudinaryJob;

class ClientServiceImpl implements ClientService
{
    private $clientRepository;
    private $fidelityCardService;
    private $cloudinaryService;

    public function __construct(
        ClientRepository $clientRepository,
        FidelityCardService $fidelityCardService,
        CloudinaryService $cloudinaryService
    ) {
        $this->clientRepository = $clientRepository;
        $this->fidelityCardService = $fidelityCardService;
        $this->cloudinaryService = $cloudinaryService;
    }

    public function getAllClients(array $filters = []): Collection
    {
        return $this->clientRepository->all($filters);
    }

    public function createClient(array $clientData, ?array $userData = null): Client
    {
       // dd(isset($clientData['user']));
       /*  dd($clientData); */
        DB::beginTransaction();
        try {
            $client = $this->clientRepository->create($clientData);

            if ($userData !== null) {
                $this->handleUserCreation($client, $userData);
            }
if(isset($clientData['user'])){

            $fidelityCardPath = $this->fidelityCardService->generateFidelityCard($clientData);
            $this->sendFidelityCardEmail($client, $fidelityCardPath);
        }

            DB::commit();

            // L'événement ClientCreated sera automatiquement dispatché par l'observer
            return $client;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function handleUserCreation($client, $userData)
    {
        if (isset($userData['photo'])) {
            $localPath = $this->handleImageUpload($userData['photo']);
            $userData['photo'] = $localPath;
            $userData['photo_upload_status'] = 'pending';
        }

        $user = $this->clientRepository->createUser($userData);
        $client->user()->associate($user);
        $client->save();

        // Nous ne dispatchez plus le job ici
        // L'événement ClientCreated se chargera de cela via le listener
    }

    public function getClientById(int $id): Client
    {
        return $this->clientRepository->findById($id);
    }

    public function searchClientByPhone(string $phone): ?Client
    {
        return $this->clientRepository->findByPhone($phone);
    }

    public function getClientWithUserInfo(int $id): array
    {
        $client = $this->clientRepository->findWithUser($id);

        if (!$client->user) {
            throw new \Exception('Ce client n\'a pas de compte utilisateur');
        }

        return [
            'client' => $client,
            'user' => $client->user
        ];
    }

    private function handleImageUpload(\Illuminate\Http\UploadedFile $image): string
    {
        try {
            $localPath = $image->store('user_images', 'public');
            $fullLocalPath = Storage::disk('public')->path($localPath);

            return $localPath;
        } catch (\Exception $e) {
            \Log::error("Échec de l'upload de l'image : " . $e->getMessage());
            throw $e;
        }
    }

    private function sendFidelityCardEmail(Client $client, string $fidelityCardPath): void
    {
        $login = $client->user->login ?? null;
        if ($login && filter_var($login, FILTER_VALIDATE_EMAIL)) {
            try {
                Mail::to($login)->send(new FidelityCardMail($client, $fidelityCardPath));
                \Log::info("Fidelity card email sent successfully for client ID: " . $client->id);
            } catch (\Exception $e) {
                \Log::error("Failed to send fidelity card email for client ID: " . $client->id . ". Error: " . $e->getMessage());
            }
        } else {
            \Log::warning("Unable to send fidelity card email. Invalid or missing email for client ID: " . $client->id);
        }
    }
}
