<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    protected $fillable = ['montant', 'detteId'];

    public function dette()
    {
        return $this->belongsTo(Dette::class, 'detteId');
    }

    protected static function booted()
    {
        static::created(function ($paiement) {
            $paiement->dette->updateStatut();
        });
    }
}
