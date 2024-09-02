<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = ['surnom', 'telephone', 'adresse', 'user_id'];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'active' => 'boolean',
        'password' => 'hashed',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
