<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    public function register(Request $request){

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user= User::create([
            'name' => $request->name,
            'email'=> $request->email,
            'password'=> Hash::make($request->password),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status'=>'success',
            'message'=>'Utilisateur inscrit avec succès',
            'access_token' => $token,
            'token_type' => 'Bearer',
        ],201);

    }

    public function login(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // 1. On check si l'utilisateur existe
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Identifiants invalides',
            ], 401);
        }

        // 2. On génère un token avec Sanctum
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Connexion réussie',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => [
                'id'=>$user->id,
                'name'=>$user->name,
                'email'=>$user->email,
            ],
        ], 200);
    }

    public function me (Request $request){
        return response()->json([
            "statut"=>"success",
            "user"=>[
            "id"=>$request->user()->id,
            "name"=>$request->user()->name,
            "email"=>$request->user()->email,
            ]
            ],200);
    }


    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status'=>'success',
            'message'=>'Déconnexion réussie',
        ]);
    }

}
