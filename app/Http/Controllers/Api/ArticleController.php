<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ArticleRequest;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use App\Services\ArticleService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function __construct()
    {
        $this->middleware('handleResponse');
    }

    public function index(Request $request): JsonResponse
    {
        $disponible = $request->query('disponible');
        $articles = $this->articleService->getArticlesByAvailability($disponible);
        return $this->successResponse(ArticleResource::collection($articles), 'Articles récupérés avec succès');
    }

    public function store(ArticleRequest $request): JsonResponse
    {
        $article = $this->articleService->createArticle($request->validated());
        return $this->successResponse(new ArticleResource($article), 'Article créé avec succès', 201);
    }

    public function show(Article $article): JsonResponse
    {
        return $this->successResponse(new ArticleResource($article), 'Article récupéré avec succès');
    }

    public function getByTitle(Request $request): JsonResponse
    {
        try {
            $request->validate(['title' => 'required|string']);
            $article = $this->articleService->findArticleByTitle($request->title);

            if (!$article) {
                return $this->errorResponse(404, 'Article non trouvé');
            }

            return $this->successResponse(new ArticleResource($article), 'Article récupéré avec succès');
        } catch (\Exception $e) {
            return $this->errorResponse(500, 'Une erreur est survenue lors de la récupération de l\'article');
        }
    }

    public function update(ArticleRequest $request, Article $article): JsonResponse
    {
        $updated = $this->articleService->updateArticle($article, $request->validated());
        return $this->successResponse(new ArticleResource($article), 'Article mis à jour avec succès');
    }

    public function destroy(Article $article): JsonResponse
    {
        $this->articleService->deleteArticle($article);
        return $this->successResponse(null, 'Article supprimé avec succès');
    }

    public function updateQuantity(Request $request, $id): JsonResponse
    {
        try {
            $request->validate(['qte' => 'required|integer']);
            $article = $this->articleService->updateArticleQuantity($id, $request->qte);

            if (!$article) {
                return $this->errorResponse(404, 'Article non trouvé');
            }

            return $this->successResponse(new ArticleResource($article), 'Quantité de l\'article mise à jour avec succès');
        } catch (\Exception $e) {
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

            $result = $this->articleService->updateArticlesStock($request->articles);

            return $this->successResponse([
                'success' => ArticleResource::collection($result['success']),
                'error' => $result['error']
            ], 'Processus de mise à jour du stock terminé');
        } catch (\Exception $e) {
            return $this->errorResponse(500, 'Une erreur est survenue lors de la mise à jour du stock');
        }
    }
}
