<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ApiUserController extends Controller
{
    public function register(Request $request){
        if(User::where('name',$request->name)->exists()){
            return response([
                'nameexists' => 'This name is already exists'
            ],422);
        }elseif(User::where('email',$request->email)->exists()){
            return response([
                'emailexists' => 'This email is already exists'
            ],422);
        }
        $validated = $request->validate([
            'name' => 'required|min:5|max:20|unique:users,name',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:5|max:20|confirmed'
        ]);
        $user = User::create([
            'name' => $request->name,
            'password' => Hash::make($request->password),
            'email' => $request->email
        ]);
        if(!Auth::attempt($request->only('email','password'),$request->remember)){
            //
        };
        $token = $user->createToken('main')->plainTextToken;

        return response([
            'user' => $user,
            'token' => $token
        ]);
    }
    public function login(Request $request){
        $validated = $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required'
        ],[
            'email.exists' => 'This email was never logged in'
        ]);

        if(!Auth::attempt($request->only('email','password'),$request->remember)){
            return response([
                'error' => 'Password is wrong'
            ],422);
        };
        $user = Auth::user();
        $token = $user->createToken('main')->plainTextToken;
        return response([
            'user'=> $user,
            'token' => $token
        ]);
    }
    public function logout(){
        $user = Auth::user();
        $user->currentAccessToken()->delete();
        return response([
            'success' => true
        ]);
    }
    public function yoxla(){
        $user = Auth::user();
        if($user){
            if($user->is_admin){
                return response ([
                    'user' => $user,
                    'auth' => true,
                    'admin' => true
                ]);
            }
        }
        return response([
            'user' => $user,
            'auth' => true,
            'admin' => false
        ]);
    }
    public function fryoxla(){
        $user = Auth::user();
        $user->wishlist = $user->wproducts()->get();
        $user->cart = $user->cproducts()->get();
        return response([
            'user' => $user,
        ]);
    }
    public function showimage(){
    $pngPath = public_path('storage/aha.png');

    $headers = [
        'Content-Type' => 'image/png',
    ];

    return response()->file($pngPath, $headers);
}
    public function dumb(){
        return "dumb";
    }
}
