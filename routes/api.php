<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DetteController;

// Routes publiques
Route::prefix('v1')->group(function () {
    Route::post('/dettes', [DetteController::class, 'store']);
    Route::get('dettes/', [DetteController::class, 'index']);
    Route::get('dettes/{id}', [DetteController::class, 'show']);
    Route::get('dettes/{id}/articles', [DetteController::class, 'articles']);
    Route::get('dettes/{id}/paiements', [DetteController::class, 'paiements']);
    Route::post('dettes/{id}/paiements', [DetteController::class, 'addPaiement']);
    // Routes d'authentification
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

});

// Routes protégées
Route::middleware('auth:api')->prefix('v1')->group(function () {
    // Users
    Route::apiResource('users', UserController::class);
    Route::post('/users', [UserController::class, 'store']);
    Route::get('/users', [UserController::class, 'index']);

    // Articles
    Route::apiResource('articles', ArticleController::class);
    Route::patch('articles/{id}/quantity', [ArticleController::class, 'updateQuantity']);
    Route::post('articles/stock', [ArticleController::class, 'updateStock']);
    Route::post('articles/title', [ArticleController::class, 'getByTitle']);

    // Clients
    Route::apiResource('clients', ClientController::class);
    Route::post('clients/telephone', [ClientController::class, 'searchByPhone']);
    Route::get('clients/{client}/user', [ClientController::class, 'getUserInfo']);
    Route::post('/clients', [ClientController::class, 'store']);
});


