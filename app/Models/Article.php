<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'price',
        'quantity',
    ];

    protected $casts = [
        'price' => 'float',
        'quantity' => 'integer',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
