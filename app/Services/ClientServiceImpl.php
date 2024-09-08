<?php

namespace App\Services;

use App\Models\Client;
use App\Models\User;
use App\Repositories\ClientRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Cloudinary\Cloudinary;

class ClientServiceImpl implements ClientService
{
    private $clientRepository;
    private $fidelityCardService;
    private $cloudinary;

    public function __construct(ClientRepository $clientRepository, FidelityCardService $fidelityCardService, Cloudinary $cloudinary)
    {
        $this->clientRepository = $clientRepository;
        $this->fidelityCardService = $fidelityCardService;
        $this->cloudinary = $cloudinary;
        // dd($this->cloudinary->);
    }

    public function getAllClients(array $filters = []): Collection
    {
        return $this->clientRepository->all($filters);
    }

    public function createClient(array $clientData, ?array $userData = null): Client
    {
        $request=request();
        $photo=$request->file('user.photo');
        // dd($photo);
        DB::beginTransaction();
        try {
            if ($userData) {
                if ($photo) {
                    $photoPath = $this->handleImageUpload($photo);
                    // dd($photoPath,"dd");
                    $userData['photo'] = $photoPath;
                }
                // dd($userData,"d55");
                $user = User::create($userData);
                $clientData['user_id'] = $user->id;
            }

            $client = $this->clientRepository->create($clientData);

            // Generate fidelity card
            $fidelityCardPath = $this->fidelityCardService->generateFidelityCard($client);

            // You might want to save the fidelity card path to the client record
            // $client->fidelity_card_path = $fidelityCardPath;
            $client->save();

            DB::commit();
            return $client;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function handleImageUpload(\Illuminate\Http\UploadedFile $image): string
    {
        try {
            // dd("dd");
            // Tentative d'upload sur Cloudinary
            $result = $this->cloudinary->uploadApi()->upload($image->getRealPath());
            return $result['secure_url'];
        } catch (\Exception $e) {
            // En cas d'échec, sauvegarde locale
            return $image->store('user_images', 'public');
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
