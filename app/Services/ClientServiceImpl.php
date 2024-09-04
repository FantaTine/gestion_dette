<?php

namespace App\Services;

use App\Models\Client;
use App\Models\User;
use App\Repositories\ClientRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class ClientServiceImpl implements ClientService
{
    private $clientRepository;

    public function __construct(ClientRepository $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }

    public function getAllClients(array $filters = []): Collection
    {
        return $this->clientRepository->all($filters);
    }

    public function createClient(array $clientData, ?array $userData = null): Client
    {
        DB::beginTransaction();

        try {
            if ($userData) {
                $user = User::create($userData);
                $clientData['user_id'] = $user->id;
            }

            $client = $this->clientRepository->create($clientData);

            DB::commit();
            return $client;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
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
