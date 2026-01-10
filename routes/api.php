<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FriendRequestController;
use App\Http\Controllers\UserMatchController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\SkillController;


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

    Route::get('/matches', [UserMatchController::class, 'index']);
    Route::get('/matches/{userMatch}', [UserMatchController::class, 'show']);
    Route::delete('/matches/{userMatch}', [UserMatchController::class, 'destroy']);


    Route::get('/matches/{match}/messages', [MessageController::class, 'index']);
    Route::post('/matches/{match}/messages', [MessageController::class, 'store']);
    Route::delete('/messages/{message}', [MessageController::class, 'destroy']);
});