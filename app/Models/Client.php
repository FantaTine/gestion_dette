<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = ['surnom', 'telephone', 'adresse', 'user_id'];
    protected $hidden = ['created_at', 'updated_at'];
    protected $casts = ['active' => 'boolean'];

    public function scopeFilter($query, array $filters)
    {
        if (isset($filters['telephone'])) {
            $query->where('telephone', 'like', $filters['telephone'] . '%');
        }
        if (isset($filters['comptes'])) {
            $query->when($filters['comptes'] === 'oui', function ($q) {
                return $q->whereNotNull('user_id');
            }, function ($q) {
                return $q->whereNull('user_id');
            });
        }
        if (isset($filters['active'])) {
            $query->whereHas('user', function ($q) use ($filters) {
                $q->where('active', $filters['active'] === 'oui');
            });
        }
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
