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
use Illuminate\Support\Str;
use App\Http\Requests\LoginRequest;
use App\Services\AuthentificationServiceInterface;


class AuthController extends Controller
{
    public function __construct(AuthentificationServiceInterface $authService)
    {
        $this->authService = $authService;
    }
 /*    public function login(Request $request)
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
    } */

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('login', 'password');
       /*   dd($credentials);  */
        $authResponse = $this->authService->authenticate($credentials);

        // Debugging output (you can remove this later)
     /*  dd($authResponse);  */

        // Check if $authResponse is an array and 'success' key exists
        if (is_array($authResponse) && isset($authResponse['success']) && $authResponse['success']) {
            // Ensure 'user' and 'token' exist in the response
            if (isset($authResponse['user']) && isset($authResponse['token'])) {
                // Use the user returned by the authService instead of retrieving it again
                $user = $authResponse['user'];

                // Generate a secure refresh token
                $refreshToken = Str::random(100);

                // Save or update the refresh token for the user in the database
                $user->update(['refresh_token' => $refreshToken]);

                return response()->json([
                    'access_token' => $authResponse['token'],
                    'token_type' => 'Bearer',
                    'refresh_token' => $refreshToken,
                    'user' => $user,
                ], 200);
            }
        }

        // Return unauthorized response if authentication failed
        return response()->json(['error' => 'Unauthorized'], 401);
    }
}
