<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kategori;
use App\Models\Admin;
use Illuminate\Support\Facades\Validator;

class KategoriController extends Controller
{
    public function index()
    {
        $kategoris = Kategori::all();
        return response()->json(['status' => 'success', 'data' => $kategoris]);
    }

    public function store(Request $request)
    {

        if (! $request->user() instanceof Admin) {
            return response()->json(['message' => 'Unauthorized. Admin Only.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'nama_kategori' => 'required|string|unique:kategoris,nama_kategori'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $kategoris = Kategori::create([
            'nama_kategori' => $request->nama_kategori
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Kategori berhasil dibuat',
            'data' => $kategoris
        ], 201);
    }

    public function update(Request $request, $id)
    {
        if (! $request->user() instanceof Admin) {
            return response()->json(['message' => 'Unauthorized. Admin Only.'], 403);
        }

        $kategoris = Kategori::find($id);
        if (!$kategoris) return response()->json(['message' => 'Kategori tidak ditemukan'], 404);

        $validator = Validator::make($request->all(), [
            'nama_kategori' => 'required|string|unique:kategoris,nama_kategori,'.$id
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $kategoris->update(['nama_kategori' => $request->nama_kategori]);

        return response()->json([
            'status' => 'success',
            'message' => 'Kategori berhasil diupdate',
            'data' => $kategoris
        ]);
    }

    public function destroy(Request $request, $id)
    {
        if (! $request->user() instanceof Admin) {
            return response()->json(['message' => 'Unauthorized. Admin Only.'], 403);
        }

        $kategoris = Kategori::find($id);
        if (!$kategoris) return response()->json(['message' => 'Kategori tidak ditemukan'], 404);

        $kategoris->delete();

        return response()->json(['status' => 'success', 'message' => 'Kategori berhasil dihapus']);
    }
}
