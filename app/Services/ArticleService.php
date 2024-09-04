<?php

namespace App\Services;

use App\Models\Article;
use Illuminate\Database\Eloquent\Collection;

interface ArticleService
{
    public function getAllArticles(): Collection;
    public function createArticle(array $data): Article;
    public function getArticle(int $id): ?Article;
    public function updateArticle(Article $article, array $data): bool;
    public function deleteArticle(Article $article): bool;
    public function findArticleByTitle(string $title): ?Article;
    public function updateArticleQuantity(int $id, int $quantity): ?Article;
    public function updateArticlesStock(array $articles): array;
}
