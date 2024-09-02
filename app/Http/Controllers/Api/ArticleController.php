<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ArticleRequest;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    use ApiResponse;

    public function __construct()
    {
        $this->authorizeResource(Article::class, 'article');
    }

    public function index(Request $request): JsonResponse
    {
        $query = Article::query();

        if ($request->has('disponible')) {
            $disponible = $request->get('disponible') === 'oui';
            $query->where('quantity', $disponible ? '>' : '=', 0);
        }

        $articles = $query->get();
        return $this->successResponse(ArticleResource::collection($articles), 'Articles récupérés avec succès');
    }

    public function store(ArticleRequest $request): JsonResponse
    {
        $article = Article::create($request->validated());
        return $this->successResponse(new ArticleResource($article), 'Article créé avec succès', 201);
    }

    public function show(Article $article): JsonResponse
    {
        return $this->successResponse(new ArticleResource($article), 'Article récupéré avec succès');
    }

    public function getByTitle(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'title' => 'required|string',
            ]);

            $article = Article::where('title', $request->title)->first();

            if (!$article) {
                return $this->errorResponse(404, 'Article non trouvé');
            }

            return $this->successResponse(new ArticleResource($article), 'Article récupéré avec succès');
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération de l\'article par titre: ' . $e->getMessage());
            return $this->errorResponse(500, 'Une erreur est survenue lors de la récupération de l\'article');
        }
    }

    public function update(ArticleRequest $request, Article $article): JsonResponse
    {
        $article->update($request->validated());
        return $this->successResponse(new ArticleResource($article), 'Article mis à jour avec succès');
    }

    public function destroy(Article $article): JsonResponse
    {
        $article->delete();
        return $this->successResponse(null, 'Article supprimé avec succès');
    }

    public function updateQuantity(Request $request, $id): JsonResponse
    {
        try {
            $request->validate([
                'qte' => 'required|integer',
            ]);

            $article = Article::find($id);

            if (!$article) {
                return $this->errorResponse(404, 'Article non trouvé');
            }

            $article->quantity += $request->qte;
            $article->save();

            return $this->successResponse(new ArticleResource($article), 'Quantité de l\'article mise à jour avec succès');
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour de la quantité: ' . $e->getMessage());
            return $this->errorResponse(500, 'Une erreur est survenue lors de la mise à jour de la quantité');
        }
    }

    public function updateStock(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'articles' => 'required|array',
                'articles.*.id' => 'required|integer',
                'articles.*.qte' => 'required|integer|min:1',
            ]);

            $success = [];
            $error = [];

            foreach ($request->articles as $articleData) {
                $article = Article::find($articleData['id']);
                if ($article) {
                    $article->quantity += $articleData['qte'];
                    $article->save();
                    $success[] = new ArticleResource($article);
                } else {
                    $error[] = $articleData['id'];
                }
            }

            return $this->successResponse([
                'success' => $success,
                'error' => $error
            ], 'Processus de mise à jour du stock terminé');
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour du stock: ' . $e->getMessage());
            return $this->errorResponse(500, 'Une erreur est survenue lors de la mise à jour du stock');
        }
    }
}

