<?php

namespace App\Repositories;

use App\Models\Article;
use Illuminate\Database\Eloquent\Collection;

class ArticleRepositoryImpl implements ArticleRepository
{
    public function all(): Collection
    {
        return Article::all();
    }

    public function create(array $data): Article
    {
        return Article::create($data);
    }

    public function find(int $id): ?Article
    {
        return Article::find($id);
    }

    public function update(Article $article, array $data): bool
    {
        return $article->update($data);
    }

    public function delete(Article $article): bool
    {
        return $article->delete();
    }

    public function findByTitle(string $title): ?Article
    {
        return Article::where('title', $title)->first();
    }

    public function updateQuantity(Article $article, int $quantity): bool
    {
        $article->quantity += $quantity;
        return $article->save();
    }

    public function updateStock(array $articles): array
    {
        $success = [];
        $error = [];

        foreach ($articles as $articleData) {
            $article = $this->find($articleData['id']);
            if ($article) {
                $this->updateQuantity($article, $articleData['qte']);
                $success[] = $article;
            } else {
                $error[] = $articleData['id'];
            }
        }

        return ['success' => $success, 'error' => $error];
    }

    public function getByAvailability(bool $isAvailable): Collection
    {
        return Article::where('quantity', $isAvailable ? '>' : '=', 0)->get();
    }
}
