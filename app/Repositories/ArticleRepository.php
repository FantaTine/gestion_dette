<?php

namespace App\Repositories;

use App\Models\Article;
use Illuminate\Database\Eloquent\Collection;

interface ArticleRepository
{
    public function all(): Collection;
    public function create(array $data): Article;
    public function find(int $id): ?Article;
    public function update(Article $article, array $data): bool;
    public function delete(Article $article): bool;
    public function findByTitle(string $title): ?Article;
    public function updateQuantity(Article $article, int $quantity): bool;
    public function updateStock(array $articles): array;
    public function getByAvailability(bool $isAvailable): Collection;
}
