<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Penyelenggara;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function createPenyelenggara(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_penyelenggara' => 'required|string|max:255',
            'email'              => 'required|email|unique:penyelenggaras,email',
            'password'           => 'required|min:6',
            'phone'              => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        // 2. Simpan ke Database
        $penyelenggara = Penyelenggara::create([
            'nama_penyelenggara' => $request->nama_penyelenggara,
            'email'              => $request->email,
            'password'           => Hash::make($request->password),
            'phone'              => $request->phone,
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Akun Penyelenggara berhasil dibuat oleh Admin.',
            'data'    => $penyelenggara
        ], 201);
    }
}
