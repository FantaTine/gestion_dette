<?php

namespace App\Repositories;

interface DetteRepositoryInterface
{
    public function create(array $data);
    public function addArticles($detteId, array $articles);
    public function addPaiement($detteId, array $paiement);
}
