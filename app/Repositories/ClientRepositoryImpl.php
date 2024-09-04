<?php

namespace App\Repositories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Collection;

class ClientRepositoryImpl implements ClientRepository
{
    public function all(array $filters = []): Collection
    {
        $query = Client::query();

        // Les filtres sont maintenant gérés par le scope global
        // Aucune logique de filtrage n'est nécessaire ici

        return $query->get();
    }

    public function create(array $data): Client
    {
        return Client::create($data);
    }

    public function findById(int $id): ?Client
    {
        return Client::findOrFail($id);
    }

    public function update(Client $client, array $data): bool
    {
        return $client->update($data);
    }

    public function delete(Client $client): bool
    {
        return $client->delete();
    }

    public function findByPhone(string $phone): ?Client
    {
        return Client::where('telephone', $phone)->first();
    }

    public function findWithUser(int $id): ?Client
    {
        return Client::with('user.role')->findOrFail($id);
    }
}
