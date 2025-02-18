<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request){
        if(Auth::attempt(['email' => $request->email,'password' => $request->password])){
            $token = Auth::user()->createToken('auth_token');
            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil login',
                'token' => $token->plainTextToken
            ],200);
        }
    }
    public function register(Request $request){
        $rules = [
            'name' => ['required','min:5','max:150','string'],
            'email' => ['required','email','unique:users,email'],
            'password' => ['required','min:10','max:150']
        ];
        $validator = Validator::make($request->all(),$rules);
        if($validator->fails()){
            return response()->json([
                'status' => 'gagal',
                'message' => 'Proses validasi gagal',
                'errors' => $validator->errors()
            ],400);
        }
        $regsitered =  User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password
        ]);        
        if(!$regsitered){
            return response()->json([
                'status' => 'error',
                'message' => 'gagal menambahkan register'
            ],400);
        }else{
            return response()->json([
                'status' => 'success',
                'message' => 'berhasil regsiter',
                'data' => [
                    'name' => $regsitered->name,
                    'email' => $regsitered->email,
                    'password' => $regsitered->password,
                ]
            ],200);
        }
        
    }
}
