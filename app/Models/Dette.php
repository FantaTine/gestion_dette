<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dette extends Model
{
    protected $fillable = ['montantDu', 'clientId', 'statut'];

    protected $casts = [
        'montantDu' => 'integer',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'clientId');
    }

    public function articles()
    {
        return $this->hasMany(DetteArticle::class, 'detteId');
    }

    public function updateStatut()
    {
        $this->statut = $this->montantRestant <= 0 ? 'soldée' : 'nonsoldée';
        $this->save();
    }

    protected $appends = ['montantRestant', 'statut'];

    public function paiements()
    {
        return $this->hasMany(Paiement::class, 'detteId');
    }

    public function getMontantRestantAttribute()
    {
        $totalPaye = $this->paiements()->sum('montant');
        return $this->montantDu - $totalPaye;
    }

    public function getStatutAttribute()
    {
        return $this->montantRestant == 0 ? 'soldée' : 'nonsoldée';
    }


}
