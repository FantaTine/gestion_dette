<?php

namespace App\Repositories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Collection;

interface ClientRepository
{
    public function all(array $filters = []): Collection;
    public function create(array $data): Client;
    public function findById(int $id): ?Client;
    public function update(Client $client, array $data): bool;
    public function delete(Client $client): bool;
    public function findByPhone(string $phone): ?Client;
    public function findWithUser(int $id): ?Client;
}
