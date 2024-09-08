<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResponseFormatterMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($response instanceof JsonResponse) {
            $content = $response->getContent();
            $data = json_decode($content, true);

            $formattedContent = [
                'status' => $response->isSuccessful() ? 'Success' : 'Error',
                'message' => $data['message'] ?? null,
                'data' => $data['data'] ?? null,
            ];

            $response->setContent(json_encode($formattedContent));
        }

        return $response;
    }
}
