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

}
