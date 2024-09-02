<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Article;
use Illuminate\Auth\Access\HandlesAuthorization;

class ArticlePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        // Tout le monde peut voir la liste des articles
        return true;
    }

    public function view(User $user, Article $article)
    {
        // Tout le monde peut voir les détails d'un article
        return true;
    }

    public function create(User $user)
    {
        // Seuls les boutiquiers et les admins peuvent créer des articles
        return in_array($user->role->name, ['boutiquier', 'admin']);
    }

    public function update(User $user, Article $article)
    {
        // Seuls les boutiquiers et les admins peuvent mettre à jour des articles
        return in_array($user->role->name, ['boutiquier', 'admin']);
    }

    public function delete(User $user, Article $article)
    {
        // Seuls les admins peuvent supprimer des articles
        return $user->role->name === 'admin';
    }

    public function updateQuantity(User $user, Article $article)
    {
        // Seuls les boutiquiers et les admins peuvent mettre à jour la quantité
        return in_array($user->role->name, ['boutiquier', 'admin']);
    }

    public function updateStock(User $user)
    {
        // Seuls les boutiquiers et les admins peuvent mettre à jour le stock
        return in_array($user->role->name, ['boutiquier', 'admin']);
    }
}
