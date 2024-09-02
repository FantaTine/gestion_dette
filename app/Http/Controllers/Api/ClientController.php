<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\User;
use App\Http\Requests\ClientRequest;
use App\Traits\ClientResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
    use ClientResponse;

    public function index(Request $request)
    {
        $query = Client::query();

        if ($request->has('telephone')) {
            $query->where('telephone', 'like', $request->telephone . '%');
        }

        if ($request->has('comptes')) {
            $query->when($request->comptes === 'oui', function ($q) {
                return $q->whereNotNull('user_id');
            }, function ($q) {
                return $q->whereNull('user_id');
            });
        }

        if ($request->has('active')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('active', $request->active === 'oui');
            });
        }

        $clients = $query->get();

        return $this->successResponse($clients, 'Liste des clients récupérée avec succès');
    }

    public function store(ClientRequest $request)
    {
        DB::beginTransaction();

        try {
            $clientData = $request->validated();
            $userData = $request->input('user');

            if ($userData) {
                $user = User::create($userData);
                $clientData['user_id'] = $user->id;
            }

            $client = Client::create($clientData);

            DB::commit();

            return $this->successResponse($client, 'Client créé avec succès', 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la création du client: ' . $e->getMessage());
            return $this->errorResponse('Erreur lors de la création du client: ' . $e->getMessage(), 500);
        }
    }

    public function show($id)
    {
        $client = Client::with('user.role')->findOrFail($id);

        return $this->successResponse($client, 'Informations du client récupérées avec succès');
    }

    public function searchByPhone(Request $request)
    {
        $request->validate([
            'telephone' => 'required|string'
        ]);

        $client = Client::where('telephone', $request->telephone)->first();

        if (!$client) {
            return $this->errorResponse('Client non trouvé', 404);
        }

        return $this->successResponse($client, 'Client trouvé avec succès');
    }

    public function getUserInfo($id)
    {
        $client = Client::with('user.role')->findOrFail($id);

        if (!$client->user) {
            return $this->errorResponse('Ce client n\'a pas de compte utilisateur', 404);
        }

        return $this->successResponse([
            'client' => $client,
            'user' => $client->user
        ], 'Informations du client et du compte utilisateur récupérées avec succès');
    }
}
