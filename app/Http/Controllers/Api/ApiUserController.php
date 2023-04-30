<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendVerificationToken;
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
        $user->role = $user->role();
        return response([
            'user' => $user,
            'token' => $token
        ]);
    }
    public function sendVerification(Request $request){
        $token = Str::random(8);
        $dozer = User::where('email',$request->email)->first();
        if($dozer){
            if($dozer->role() === 'admin' || $dozer->role() === 'moderator'){
                return response([
                    'message' => 1
                ],422); 
            }
            if($dozer->email_verified_at != null){
                return response([
                    'message' => 1
                ],422); 
            }
        }
        $user = User::updateOrCreate(
            [
                'email' => $request->email
            ],
            [
                'name' => $request->name,
                'password' => Hash::make($request->password),
                'email' => $request->email,
                'verification_token' => $token,
                'verification_expire_date' => Carbon::now()->addMinutes(60)
            ]);
        Mail::to($user->email)->send(new SendVerificationToken($token));
    }
    public function verificate(Request $request){
        $user = User::where('email',$request->email)->first();
        if($user->verification_expire_date < now()){
            $user->update([
                'verification_token' => null
            ]);
            return response([
                'message' => 2
            ],422);
        }
        if($request->v_token != $user->verification_token){
            return response([
                'message' => 1
            ],422);
        }
        $user->update([
            'verification_token' => null,
            'email_verified_at' => Carbon::now(),
            'verification_expire_date' => null
        ]);
        if(!Auth::attempt(['email'=>$user->email,'password'=>$user->password],$request->remember)){
            //
        };
        $token = $user->createToken('main')->plainTextToken;
        $user->role = $user->role();
        $user->wishlist = $user->wproducts()->get();
        $user->cart = $user->cproducts()->get();
        return response([
            'user' => $user,
            'token' => $token
        ]);
    }

    public function forgotpassword(Request $request){
        $user = User::where('email',$request->email)->first();
        $token = Str::random(8);
        $user->update([
            'verification_token' => $token,
            'verification_expire_date' => Carbon::now()->addMinutes(60)
        ]);
        Mail::to($user->email)->send(new SendVerificationToken($token));
    }
    public function setNew(Request $request){
        $user = User::where('email',$request->email)->first();
        $user->update([
            'password'=>Hash::make($request->new_password)
        ]);
        $token = $user->createToken('main')->plainTextToken;
        $user->role = $user->role();
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
        $dozer = User::where('email',$request->email)->first();
        if($dozer->email_verified_at === null && $dozer->role() != 'admin' && $dozer->role() != 'moderator'){
            return response([
                'message' => 'This email does not exists'
            ],422);
        }
        if(!Auth::attempt($request->only('email','password'),$request->remember)){
            return response([
                'message' => 'Password is wrong'
            ],422);
        };
        $user = Auth::user();
        $token = $user->createToken('main')->plainTextToken;
        $user->role = $user->role();
        $user->wishlist = $user->wproducts()->get();
        $user->cart = $user->cproducts()->get();
        return response([
            'user'=> $user,
            'token' => $token
        ]);
    }
    public function admin_login(Request $request){
        $validated = $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required'
        ],[
            'email.exists' => 'This email does not exists'
        ]);
        $dozer = User::where('email',$request->email)->first();
        if($dozer->role() != 'admin' && $dozer->role() != 'moderator'){
            return response([
                'message' =>  2 //'You have no permission to log in'
            ],422);
        }
        if(!Auth::attempt($request->only('email','password'),$request->remember)){
            return response([
                'message' => 3 //'Password is wrong'
            ],422);
        };
        $user = Auth::user();
        $token = $user->createToken('main')->plainTextToken;
        $user->role = $user->role();
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
        $user->role = $user->role();
        if($user){
            if($user->role() === 'admin' || $user->role() === 'moderator'){
                return response ([
                    'user' => $user,
                    'auth' => true,
                    'admin' => true
                ]);
            }
            return response([
                'message' => 'Unauthanticated.'
            ],422);
        }
    }
    public function fryoxla(){
        $user = Auth::user();
        $user->role = $user->role();
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
