<?php

namespace App\Http\Controllers;
use App\Models\User;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function search(Request $request){
        $query=$request->query('q');
        if (!$query) {
            return response()->json([
                'status'=>'success',
                'data'=>[]
            ]);
        }

        $users = User::where('name', 'like', '%' . $query . '%')
        ->with('skills')
        ->limit(10)
        ->get();
        
        return response()->json([
            'status'=>'success',
            'data'=>$users
        ]);
    }

    public function show($id) {
      $user=User::with('skills')->FindOrFail($id);
      return response()->json([
        'status'=>'success',
        'data'=>$user
      ]);
    }

    public function update(Request $request)
{
    $user = $request->user();

    $request->validate([
    'name'  => 'sometimes|string|max:255',
    'email' => 'sometimes|email|max:255',
    'bio'   => 'sometimes|nullable|string|max:500',
]);

    $user->update($request->only(['name','email', 'bio']));

    return response()->json([
        'status' => 'success',
        'data'   => $user
    ]);
}

   public function uploadPhoto(Request $request)
{
    $request->validate([
        'photo' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048'
    ]);

    $user = $request->user();

    // Supprimer l'ancienne photo si elle existe
    if ($user->profile_picture) {
        $oldPath = str_replace('/storage/', 'public/', $user->profile_picture);
        \Storage::delete($oldPath);
    }

    $path = $request->file('photo')->store('profile_pictures', 'public');

    $user->update([
        'profile_picture' => '/storage/' . $path
    ]);

    return response()->json([
        'status' => 'success',
        'profile_picture' => '/storage/' . $path
    ]);
}

}
