<?php

namespace App\Services;

use App\Models\Client;
use Illuminate\Database\Eloquent\Collection;

interface ClientService
{
    public function getAllClients(array $filters = []): Collection;
    public function createClient(array $clientData, ?array $userData = null): Client;
    public function getClientById(int $id): Client;
    public function searchClientByPhone(string $phone): ?Client;
    public function getClientWithUserInfo(int $id): array;
}
