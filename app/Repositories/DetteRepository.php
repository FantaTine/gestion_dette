<?php

namespace App\Repositories;

use App\Models\Dette;
use App\Models\DetteArticle;
use App\Models\Paiement;

class DetteRepository implements DetteRepositoryInterface
{
    public function create(array $data)
    {
        return Dette::create($data);
    }

    public function addArticles($detteId, array $articles)
    {
        $detteArticles = [];
        foreach ($articles as $article) {
            $detteArticles[] = new DetteArticle($article);
        }
        Dette::find($detteId)->articles()->saveMany($detteArticles);
    }

    public function addPaiement($detteId, array $paiement)
    {
        Dette::find($detteId)->paiements()->create($paiement);
    }

    public function getAllWithClient($statut = null)
    {
        $query = Dette::with('client');

        if ($statut === 'solde') {
            $query->where('montantRestant', 0);
        } elseif ($statut === 'nonsolde') {
            $query->where('montantRestant', '>', 0);
        }

        return $query->get();
    }

    public function getWithClient($id)
    {
        return Dette::with('client')->find($id);
    }

    public function getWithArticles($id)
    {
        return Dette::with(['client', 'articles'])->find($id);
    }

    public function getWithPaiements($id)
    {
        return Dette::with(['client', 'paiements'])->find($id);
    }

    public function find($id)
    {
        return Dette::find($id);
    }

}
