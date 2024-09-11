<?php

namespace App\Services;

use App\Repositories\DetteRepositoryInterface;
use App\Models\Article;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;
use Exception;

class DetteService implements DetteServiceInterface
{
    protected $detteRepository;

    public function __construct(DetteRepositoryInterface $detteRepository)
    {
        $this->detteRepository = $detteRepository;
    }

    public function createDette(array $data)
    {
        DB::beginTransaction();

        try {
            $dette = $this->detteRepository->create([
                'montantDu' => $data['montantDu'],
                'clientId' => $data['clientId'],
            ]);

            $this->detteRepository->addArticles($dette->id, $data['articles']);

            foreach ($data['articles'] as $article) {
                Article::find($article['articleId'])->decrement('qteStock', $article['qteVente']);
            }

            if (isset($data['paiement'])) {
                $this->detteRepository->addPaiement($dette->id, $data['paiement']);
            }

            DB::commit();
            return $dette;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getAllDettes($statut = null): Collection
    {
        $dettes = $this->detteRepository->getAllWithClient();

        if ($statut !== null) {
            $dettes = $dettes->filter(function ($dette) use ($statut) {
                if ($statut === 'soldée') {
                    return $dette->montantRestant == 0;
                } elseif ($statut === 'nonsoldée') {
                    return $dette->montantRestant > 0;
                }
                return true;
            });
        }

        return $dettes;
    }

    public function getDette($id)
    {
        return $this->detteRepository->getWithClient($id);
    }

    public function getDetteWithArticles($id)
    {
        return $this->detteRepository->getWithArticles($id);
    }

    public function getDetteWithPaiements($id)
    {
        return $this->detteRepository->getWithPaiements($id);
    }

    public function addPaiement($id, $montant)
    {
        DB::beginTransaction();

        try {
            $dette = $this->detteRepository->find($id);

            if (!$dette) {
                throw new Exception("Dette non trouvée");
            }

            if ($montant > $dette->montantRestant) {
                throw new Exception("Le montant du paiement ne peut pas dépasser le montant restant dû");
            }

            $paiement = $this->detteRepository->addPaiement($id, ['montant' => $montant, 'detteId' => $id]);

            DB::commit();
            return $dette->fresh()->load('paiements');
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

}
