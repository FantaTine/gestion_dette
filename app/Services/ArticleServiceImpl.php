<?php

namespace App\Services;

use App\Models\Article;
use App\Repositories\ArticleRepository;
use Illuminate\Database\Eloquent\Collection;

class ArticleServiceImpl implements ArticleService
{
    private $articleRepository;

    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    public function getAllArticles(): Collection
    {
        return $this->articleRepository->all();
    }

    public function createArticle(array $data): Article
    {
        return $this->articleRepository->create($data);
    }

    public function getArticle(int $id): ?Article
    {
        return $this->articleRepository->find($id);
    }

    public function updateArticle(Article $article, array $data): bool
    {
        return $this->articleRepository->update($article, $data);
    }

    public function deleteArticle(Article $article): bool
    {
        return $this->articleRepository->delete($article);
    }

    public function findArticleByTitle(string $title): ?Article
    {
        return $this->articleRepository->findByTitle($title);
    }

    public function updateArticleQuantity(int $id, int $quantity): ?Article
    {
        $article = $this->getArticle($id);
        if ($article && $this->articleRepository->updateQuantity($article, $quantity)) {
            return $article;
        }
        return null;
    }

    public function updateArticlesStock(array $articles): array
    {
        return $this->articleRepository->updateStock($articles);
    }

    public function getArticlesByAvailability(?string $disponible): Collection
    {
        if ($disponible === null) {
            return $this->articleRepository->all();
        }

        $isAvailable = strtolower($disponible) === 'oui';
        return $this->articleRepository->getByAvailability($isAvailable);
    }
}
