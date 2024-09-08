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
    private $cloudinary;

    public function __construct(ClientRepository $clientRepository, FidelityCardService $fidelityCardService, CloudinaryService $cloudinaryservice)
    {
        $this->clientRepository = $clientRepository;
        $this->fidelityCardService = $fidelityCardService;
        $this->cloudinaryservice = $cloudinaryservice;
    }

    public function getAllClients(array $filters = []): Collection
    {
        return $this->clientRepository->all($filters);
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

    public function createClient(array $clientData, ?array $userData = null): Client
    {
        DB::beginTransaction();
        try {
            if ($userData) {
                $photo = request()->file('user.photo');
                if ($photo) {
                    $localPath = $this->handleImageUpload($photo);
                    $userData['photo'] = $localPath;
                    $userData['photo_upload_status'] = 'pending';
                }

                $user = User::create($userData);
                $clientData['user_id'] = $user->id;

                if (isset($localPath)) {
                    UploadToCloudinaryJob::dispatch($user, Storage::disk('public')->path($localPath));
                }
            }

            $client = $this->clientRepository->create($clientData);

            $fidelityCardPath = $this->fidelityCardService->generateFidelityCard($client);

            $this->sendFidelityCardEmail($client, $fidelityCardPath);

            DB::commit();
            return $client;
        } catch (\Exception $e) {
            DB::rollBack();
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
}
