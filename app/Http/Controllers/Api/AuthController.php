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
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Valider les informations d'identification entrées par l'utilisateur
        $credentials = $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        // Tentative de connexion avec les informations d'identification fournies
        if (Auth::attempt($credentials)) {
            // Si l'authentification est réussie, redirigez l'utilisateur
            $user = Auth::user();
            $token = $user->createToken('LaravelPassportAuth')->accessToken; // Créer un token si vous utilisez Laravel Passport

            return response()->json([
                'message' => 'Connexion réussie',
                'user' => $user,
                'token' => $token
            ], 200);
        } else {
            // Si l'authentification échoue, renvoyez une réponse avec une erreur
            return response()->json(['message' => 'Informations d\'identification invalides'], 401);
        }
    }
}
