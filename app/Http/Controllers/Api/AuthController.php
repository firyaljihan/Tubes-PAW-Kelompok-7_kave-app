<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin;
use App\Models\Mahasiswa;
use App\Models\Penyelenggara;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        $admin = Admin::where('email', $request->email)->first();
        if ($admin && Hash::check($request->password, $admin->password)) {
            $token = $admin->createToken('admin_token')->plainTextToken;
            return response()->json([
                'status' => 'success',
                'role' => 'admin',
                'message' => 'Login Berhasil sebagai Admin',
                'data' => $admin,
                'token' => $token
            ]);
        }

        $mahasiswa = Mahasiswa::where('email', $request->email)->first();
        if ($mahasiswa && Hash::check($request->password, $mahasiswa->password)) {
            $token = $mahasiswa->createToken('mahasiswa_token')->plainTextToken;
            return response()->json([
                'status' => 'success',
                'role' => 'mahasiswa',
                'message' => 'Login Berhasil sebagai Mahasiswa',
                'data' => $mahasiswa,
                'token' => $token
            ]);
        }

        $penyelenggara = Penyelenggara::where('email', $request->email)->first();
        if ($penyelenggara && Hash::check($request->password, $penyelenggara->password)) {
            $token = $penyelenggara->createToken('penyelenggara_token')->plainTextToken;
            return response()->json([
                'status' => 'success',
                'role' => 'penyelenggara',
                'message' => 'Login Berhasil sebagai Penyelenggara',
                'data' => $penyelenggara,
                'token' => $token
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Email atau Password salah.'
        ], 401);
    }

  public function registerMahasiswa(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nim'           => 'required|unique:mahasiswas,nim',
            'nama'          => 'required|string|max:255',
            'prodi'         => 'required|string',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'email'         => 'required|email|unique:mahasiswas,email',
            'password'      => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $mahasiswa = Mahasiswa::create([
            'nim'           => $request->nim,
            'nama'          => $request->nama,
            'prodi'         => $request->prodi,
            'jenis_kelamin' => $request->jenis_kelamin,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
        ]);

        $token = $mahasiswa->createToken('mahasiswas_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Registrasi Mahasiswa Berhasil',
            'data' => $mahasiswa,
            'token' => $token
        ], 201);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil Logout'
        ]);
    }
}
