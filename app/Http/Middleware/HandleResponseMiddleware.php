<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class HandleResponseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Logique pour gérer les réponses
        if ($response instanceof \Illuminate\Http\JsonResponse) {
            // Ajouter une logique personnalisée si nécessaire
            return response()->json([
                'status' => 'success',
                'data' => $response->getData(),
            ], $response->getStatusCode());
        }

        return $response;
    }
}
