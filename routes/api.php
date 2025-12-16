<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FriendRequestController;



Route::post ('/register', [AuthController::class, 'register']);
Route::post ('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function(){
    Route::get('/me', [AuthController::class, 'me']);
    Route::post ('/logout', [AuthController::class, 'logout']);

    // Envoyer une demande d'ami
    Route::post('/friend-requests', [FriendRequestController::class, 'store']);

    // Voir les demandes reçues
    Route::get('/friend-requests', [FriendRequestController::class, 'index']);

    // Voir une demande spécifique
    Route::get('/friend-requests/{friendRequest}', [FriendRequestController::class, 'show']);

    // Accepter / refuser une demande
    Route::put('/friend-requests/{friendRequest}', [FriendRequestController::class, 'update']);

    // Annuler / supprimer
    Route::delete('/friend-requests/{friendRequest}', [FriendRequestController::class, 'destroy']);
});