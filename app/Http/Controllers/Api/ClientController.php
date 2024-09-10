<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClientRequest;
use App\Services\ClientService;
use Illuminate\Http\Request;
use App\Services\ClientServiceImpl;

class ClientController extends Controller
{
    private $clientService;

    public function __construct(ClientService $clientService)
    {
        $this->clientService = $clientService;
    }

    public function index(Request $request)
    {
        return $this->clientService->getAllClients($request->all());
    }

    public function store(ClientRequest $request)
    {
        return $this->clientService->createClient($request->validated());
    }

    public function show($id)
    {
        return $this->clientService->getClientById($id);
    }

    public function searchByPhone(Request $request)
    {
        return $this->clientService->searchClientByPhone($request->telephone);
    }

    public function getUserInfo($id)
    {
        return $this->clientService->getClientWithUserInfo($id);
    }
}
