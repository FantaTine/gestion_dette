<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetteArticle extends Model
{
    protected $fillable = ['detteId', 'articleId', 'qteVente', 'prixVente'];

    protected $casts = [
        'prixVente' => 'integer',
    ];

    public function dette()
    {
        return $this->belongsTo(Dette::class, 'detteId');
    }

    public function article()
    {
        return $this->belongsTo(Article::class, 'articleId');
    }
}
