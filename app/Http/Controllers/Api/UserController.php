<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Models\Role;
use App\Traits\UserResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    use UserResponse;

    public function __construct()
{
    $this->authorizeResource(User::class, 'user');
}

    public function index(Request $request): JsonResponse
    {
        $query = User::query();

        if ($request->has('role')) {
            $role = Role::where('name', $request->role)->first();
            if ($role) {
                $query->where('role_id', $role->id);
            }
        }

        if ($request->has('active')) {
            $active = $request->active === 'oui';
            $query->where('active', $active);
        }

        $users = $query->with('role')->get();

        $formattedUsers = $users->map(function ($user) {
            return $this->formatUserData($user);
        });

        return $this->successResponse(
            $formattedUsers,
            'Liste des utilisateurs récupérée avec succès',
            $users->isEmpty() ? 204 : 200
        );
    }

    public function store(UserRequest $request): JsonResponse
    {
        try {
            $userData = $request->validated();

            // Hacher le mot de passe
            $userData['password'] = Hash::make($userData['password']);

            // Gérer l'attribut 'active'
            $userData['active'] = $request->has('active') ? (bool)$request->active : true;

            if ($request->hasFile('photo')) {
                $path = $request->file('photo')->store('users', 'public');
                $userData['photo'] = $path;
            }

            $user = User::create($userData);

            return $this->successResponse(
                $this->formatUserData($user),
                'Utilisateur créé avec succès',
                201
            );
        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];
            if($errorCode == 1062){
                return $this->errorResponse('Un utilisateur avec ce login ou ce numéro de téléphone existe déjà.', 409);
            }
            return $this->errorResponse('Une erreur est survenue lors de la création de l\'utilisateur.', 500);
        }
    }

    protected function formatUserData($user): array
    {
        $data = [
            'id' => $user->id,
            'nom' => $user->nom,
            'prenom' => $user->prenom,
            'telephone' => $user->telephone,
            'role' => $user->role->name,
            'login' => $user->login,
            'active' => $user->active
        ];

        if ($user->photo) {
            $data['photo'] = url(Storage::url($user->photo));
        }

        return $data;
    }

}
