<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request){
        if(Auth::attempt(['email' => $request->email,'password' => $request->password])){
            $token = Auth::user()->createToken('auth_token');
            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil login',
                'token' => $token->plainTextToken
            ]);
        }
    }
}
