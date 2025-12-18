<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $penyelenggaraId = $request->user()->id;

        $events = Event::where('penyelenggara_id', $penyelenggaraId)
                        ->orderBy('created_at', 'desc')
                        ->get();

        return response()->json(['status' => 'success', 'data' => $events]);
    }

    public function store(Request $request)
    {
        // Validasi nama kolom sesuai gambar tabel
        $validator = Validator::make($request->all(), [
            'title'       => 'required|string|max:255',
            'description' => 'required',
            'start_time'  => 'required|date_format:Y-m-d H:i:s',
            'end_time'    => 'required|date_format:Y-m-d H:i:s|after:start_time',
            'location'    => 'required|string',
            'quota'       => 'required|integer',
            'price'       => 'required|integer',
            'kategori_id' => 'required|integer',
            'banner'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $bannerPath = null;
        if ($request->hasFile('banner')) {
            $bannerPath = $request->file('banner')->store('banners', 'public');
        }

        $event = Event::create([
            'title'            => $request->title,
            'description'      => $request->description,
            'start_time'       => $request->start_time,
            'end_time'         => $request->end_time,
            'location'         => $request->location,
            'quota'            => $request->quota,
            'price'            => $request->price,
            'kategori_id'      => $request->kategori_id,
            'status'           => 'draft',
            'banner'           => $bannerPath,
            'penyelenggara_id' => $request->user()->id,
        ]);

        return response()->json([
            'status' => 'success',
            'message'=> 'Event berhasil dibuat',
            'data'   => $event
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $event = Event::where('id', $id)
                      ->where('penyelenggara_id', $request->user()->id)
                      ->first();

        if (!$event) return response()->json(['message' => 'Event tidak ditemukan/bukan milik Anda'], 404);

        $validator = Validator::make($request->all(), [
            'title'       => 'string',
            'start_time'  => 'date_format:Y-m-d H:i:s',
            'end_time'    => 'date_format:Y-m-d H:i:s|after:start_time',
            'banner'      => 'nullable|image|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Logic Ganti Gambar
        if ($request->hasFile('banner')) {
            if ($event->banner && Storage::disk('public')->exists($event->banner)) {
                Storage::disk('public')->delete($event->banner);
            }
            $event->banner = $request->file('banner')->store('banners', 'public');
            $event->save();
        }


        $event->update($request->except(['banner']));

        return response()->json(['status' => 'success', 'message'=> 'Event diupdate', 'data' => $event]);
    }

    public function destroy(Request $request, $id)
    {
        $event = Event::where('id', $id)
                      ->where('penyelenggara_id', $request->user()->id)
                      ->first();

        if (!$event) return response()->json(['message' => 'Event tidak ditemukan'], 404);

        if ($event->banner) {
            Storage::disk('public')->delete($event->banner);
        }
        $event->delete();

        return response()->json(['status' => 'success', 'message' => 'Event dihapus']);
    }

    public function show($id) {
         $event = Event::find($id);
         if (!$event) return response()->json(['message' => 'Not Found'], 404);
         return response()->json(['data' => $event]);
    }
}
