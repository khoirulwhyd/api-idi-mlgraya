<?php

namespace App\Http\Controllers;

use Auth;
use Validator;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;

class PenggunaController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'npaidi' => 'required|string',
            'nik' => 'required|string|max:16',
            'nama_lengkap' => 'required|string',
            'no_telepon' => 'required|min:10|max:13',
            'email' => 'required|email',
            'password' => 'required_with:ulangi_password|same:ulangi_password|string|min:8',
            'ulangi_password' => 'required|string|min:8',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());       
        }

        $pengguna = Pengguna::create([
            'npaidi' => $request->npaidi,
            'nik' => $request->nik,
            'nama_lengkap' => $request->nama_lengkap,
            'no_telepon' => $request->no_telepon,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'ulangi_password' => Hash::make($request->ulangi_password),
        ]);

        $token = $pengguna->createToken('auth_token')->plainTextToken;

        return response()->json([
            'data' => $pengguna,
            'token' => $token,
            'token_type' => 'Bearer'
        ]);
    }

    public function login(Request $request)
    {
        if(!Auth::attempt($request->all(), [
            'npaidi' => 'required|string',
            'nik' => 'required|string|max:16',
            'nama_lengkap' => 'required|string',
            'no_telepon' => 'required|min:10|max:13',
            'email' => 'required|email',
            'password' => 'required_with:ulangi_password|same:ulangi_password|string|min:8',
            'ulangi_password' => 'required|string|min:8',
        ]))
        {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $pengguna = Pengguna::where('email', $request['email'])->firstOrFail();

        $token = $pengguna->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Hi '.$pengguna->nama_lengkap.', welcome to home',
            'token' => $token,
            'token_type' => 'Bearer'
        ]); 
    }

    public function logout()
    {
        auth()->pengguna()->tokens()->delete();

        return [
            'message' => 'You have successfully logged out and the token was successfully deleted'
        ];    
    }
}