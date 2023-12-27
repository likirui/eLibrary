<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
class RegisterController extends Controller
{

    public function __construct() {
        $this->middleware('auth:api', ['except' => ['refresh']]);
    }

    public function index()
    {
        $users = User::whereNull('deleted_at')->get();
        return response()->json([
            'status' => 'success',
            'users' => $users,
        ]);
    }

   public function store(Request $request){
    /*$input = $request->all();
    User::create([
      'name' => $input['name'],
      'email'=>$input['email'],
      'password'=>Hash::make($input['password']),
    ]);
    return response()->json(['status'=>true, 'message'=>'Registration Success']);*/
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'role' => 'required',
        'physical_address' =>'required',
        'password' => 'required|string|min:6',
    ]);



    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'role'=>$request->role,
        'physical_address'=>$request->physical_address,
        'password' => Hash::make($request->password),
    ]);

    return response()->json([
        'message' => 'User created successfully',
        'user' => $user,
    ]);
}

    public function show(Request $request, $id)
    {
        $user = User::find($id);
        return response()->json([
            'status' => 'success',
            'user' => $user,
        ]);
    }

    public function refresh()
    {
        return response()->json([
            'user' => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }

    public function update(Request $request, $id){
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'role' => 'required',
            'physical_address' =>'required',
            'password' => 'required|string|min:6',
        ]);

        $user = User::find($id);

        if ($user) {
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'role'=>$request->role,
                'physical_address'=>$request->physical_address,
                'password' => Hash::make($request->password),
            ]);

            return response()->json([
                'message' => 'User updated successfully',
                'user' => $user,
            ]);
        } else {
            return response()->json([
                'message' => 'User not found',
            ]);
        }
     }
}
