<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{

    public function __construct() {
        $this->middleware('auth:api', ['except' => ['check', 'logout']]);
    }
    public function check(Request $request){
        $credentials = $request->validate([
            'email'=>'required','email',
            'password'=>'required',
        ]);
        $credentials = $request->only('email', 'password');
        $token = Auth::attempt($credentials);

        if (!$token) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 401);
        }

        $user = Auth::user();
        return response()->json([
            'user' => $user,
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);

 }

 public function logout()
 {
     Auth::logout();
     return response()->json([
         'message' => 'Successfully logged out',
     ]);
 }

}
