<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClientRequest;
use App\Services\ClientService;
use App\Traits\ClientResponse;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    use ClientResponse;

    private $clientService;

    public function __construct(ClientService $clientService)
    {
        $this->clientService = $clientService;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['telephone', 'comptes', 'active']);
        $clients = $this->clientService->getAllClients($filters);
        return $this->successResponse($clients, 'Liste des clients récupérée avec succès');
    }

    public function store(ClientRequest $request)
    {
        try {
            $client = $this->clientService->createClient($request->validated(), $request->input('user'));
            return $this->successResponse($client, 'Client créé avec succès', 201);
        } catch (\Exception $e) {
            return $this->errorResponse('Erreur lors de la création du client: ' . $e->getMessage(), 500);
        }
    }

    public function show($id)
    {
        $client = $this->clientService->getClientById($id);
        return $this->successResponse($client, 'Informations du client récupérées avec succès');
    }

    public function searchByPhone(Request $request)
    {
        $request->validate(['telephone' => 'required|string']);

        $client = $this->clientService->searchClientByPhone($request->telephone);

        if (!$client) {
            return $this->errorResponse('Client non trouvé', 404);
        }

        return $this->successResponse($client, 'Client trouvé avec succès');
    }

    public function getUserInfo($id)
    {
        try {
            $data = $this->clientService->getClientWithUserInfo($id);
            return $this->successResponse($data, 'Informations du client et du compte utilisateur récupérées avec succès');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 404);
        }
    }
}
