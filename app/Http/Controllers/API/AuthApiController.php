<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthApiController extends Controller
{
    public function login(Request $request)
    {
        // Validasi request
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required|min:6',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors(),
            ], 422); // 422 Unprocessable Entity
        }

        // Cari user berdasarkan email
        $user = User::where('email', $request->email)->first();

        // Cek apakah user ada dan password benar
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Email atau password salah', 
            ], 401); // 401 Unauthorized
        }

        $token = JWTAuth::fromUser($user, [
            'name' => $user->name,
            'email' => $user->email,
            'avatar_url' => $user->avatar_url,
            // ... data lainnya
        ]);        
        // Tambahkan klaim custom (email) ke dalam payload JWT
        $customPayload = [
            'email' => $request->email,
        ];

        // Generate token dengan klaim tambahan
        $token = JWTAuth::claims($customPayload)->fromUser($user);        

        // Mengembalikan token JWT dengan status code 200
        return response()->json([
            'status' => true,
            'token' => $token,
            'name'  => $user->name,
            'email'  => $user->email,
            'avatar_url'  => getenv('APP_URL') . '/storage/'  . $user->avatar_url,
            'message' => 'Login berhasil',
        ], 200); 
    }
}