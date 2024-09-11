<?php

namespace App\Http\Controllers\Api;

use App\Models\Article;
use App\Models\Dette;
use App\Models\DetteArticle;
use App\Models\Paiement;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\DetteServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Exception;

class DetteController extends Controller
{
    protected $detteService;

    public function __construct(DetteServiceInterface $detteService)
    {
        $this->detteService = $detteService;
    }

    public function index(Request $request): JsonResponse
    {
        $statut = $request->query('statut');

        // Validation du paramètre statut
        if ($statut && !in_array($statut, ['soldée', 'nonsoldée'])) {
            return response()->json([
                'status' => 400,
                'message' => 'Le statut doit être "solde" ou "nonsolde"'
            ], 400);
        }

        $dettes = $this->detteService->getAllDettes($statut);

        if ($dettes->isEmpty()) {
            return response()->json([
                'status' => 200,
                'data' => null,
                'message' => 'Pas de Dettes'
            ]);
        }

        return response()->json([
            'status' => 200,
            'data' => $dettes,
            'message' => 'Liste des dettes'
        ]);
    }

    public function show($id): JsonResponse
    {
        $dette = $this->detteService->getDette($id);

        if (!$dette) {
            return response()->json([
                'status' => 411,
                'data' => null,
                'message' => 'Objet non trouvé'
            ]);
        }

        return response()->json([
            'status' => 200,
            'data' => $dette,
            'message' => 'Dette trouvée'
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'montantDu' => 'required|numeric|min:0',
            'clientId' => 'required|exists:clients,id',
            'articles' => 'required|array',
            'articles.*.articleId' => 'required|exists:articles,id',
            'articles.*.qteVente' => 'required|integer|min:1',
            'articles.*.prixVente' => 'required|numeric|min:0',
            'paiement.montant' => 'required|numeric|min:0',
        ]);

        try {
            $dette = $this->detteService->createDette($request->all());
            return response()->json(['message' => 'Dette créée avec succès', 'data' => $dette], 201);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function articles($id): JsonResponse
    {
        $articles = $this->detteService->getDetteWithArticles($id);

        if (!$articles) {
            return response()->json([
                'status' => 411,
                'data' => null,
                'message' => 'Articles non trouvés'
            ]);
        }

        return response()->json([
            'status' => 200,
            'data' => $articles,
            'message' => 'Articles de la dette'
        ]);
    }

    public function paiements($id): JsonResponse
    {
        $paiements = $this->detteService->getDetteWithPaiements($id);

        if (!$paiements) {
            return response()->json([
                'status' => 411,
                'data' => null,
                'message' => 'Paiements non trouvés'
            ]);
        }

        return response()->json([
            'status' => 200,
            'data' => $paiements,
            'message' => 'Paiements de la dette'
        ]);
    }

    public function addPaiement(Request $request, $id): JsonResponse
    {
        $request->validate([
            'montant' => 'required|numeric|min:0'
        ]);

        try {
            $paiement = $this->detteService->addPaiement($id, $request->montant);
            return response()->json([
                'status' => 200,
                'data' => $paiement,
                'message' => 'Paiement ajouté'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 422,
                'data' => null,
                'message' => $e->getMessage()
            ], 422);
        }
    }
}
