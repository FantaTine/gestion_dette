<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
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

    protected static function booted()
    {
        static::addGlobalScope('filters', function (Builder $builder) {
            $filters = request()->only(['telephone', 'comptes', 'active']);

            if (isset($filters['telephone'])) {
                $builder->where('telephone', 'like', $filters['telephone'] . '%');
            }

            if (isset($filters['comptes'])) {
                $builder->when($filters['comptes'] === 'oui', function ($query) {
                    return $query->whereNotNull('user_id');
                }, function ($query) {
                    return $query->whereNull('user_id');
                });
            }

            if (isset($filters['active'])) {
                $builder->whereHas('user', function ($query) use ($filters) {
                    $query->where('active', $filters['active'] === 'oui');
                });
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
