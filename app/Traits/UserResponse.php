<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait UserResponse
{
    /**
     * Return a success response.
     *
     * @param mixed $data
     * @param string $message
     * @param int $code
     * @return JsonResponse
     */
    protected function successResponse($data, string $message = '', int $code = 200): JsonResponse
    {
        return response()->json([
            'status' => 'Success',
            'message' => $message,
            'data' => $data
        ], $code);
    }

    /**
     * Return an error response.
     *
     * @param string $message
     * @param int $code
     * @return JsonResponse
     */
    protected function errorResponse(string $message, int $code): JsonResponse
    {
        return response()->json([
            'status' => 'Error',
            'message' => $message,
            'data' => null
        ], $code);
    }

    /**
     * Format user data for response.
     *
     * @param mixed $user
     * @return array
     */
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
