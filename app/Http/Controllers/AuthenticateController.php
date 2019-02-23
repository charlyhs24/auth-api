<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JWTAuth;
use App\User;
use Tymon\JWTAuth\Exceptions\JWTException;
class AuthenticateController extends Controller
{
    //
    public function index(){
        return "hai";
    }
    public function signup(Request $request){
        $this->validate($request,[
            'name' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required',
        ]);
        return User::create([
            'name' => $request->json('name'),
            'email' => $request->json('email'),
            'password' => bcrypt($request->json('password')),
        ]);
        return response()->json('registrasi akun berhasil');
    }
    public function signin(Request $request){
        // grab credentials from the request
        $credentials = $request->only('email', 'password');
        // dd($credentials);
        try {
            // attempt to verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
        // all good so return the token
        return response()->json([
            'user_id'=> $request->user()->id,
            'name'=> $request->user()->name,
            'email'=> $request->user()->email,
            'token' => $token
        ]);
 
    }
}
