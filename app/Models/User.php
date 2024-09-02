<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'nom', 'prenom', 'telephone', 'role_id', 'login', 'password', 'active', 'photo'
    ];

    protected $hidden = [
        'created_at', 'updated_at',
    ];

    protected $casts = [
        'active' => 'boolean',
        'password' => 'hashed',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function client()
    {
        return $this->hasOne(Client::class);
    }
}
