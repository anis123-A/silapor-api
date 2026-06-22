<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notifikasi;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    // GET /api/notifikasi
    public function index(Request $request)
    {
        $notifikasi = Notifikasi::where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success'      => true,
            'notifikasi'   => $notifikasi,
            'belum_dibaca' => $notifikasi->where('is_read', 0)->count(),
        ]);
    }

    // POST /api/notifikasi/{id}/baca
    public function markAsRead(Request $request, $id)
    {
        Notifikasi::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->update(['is_read' => 1]);

        return response()->json(['success' => true]);
    }

    // POST /api/notifikasi/baca-semua
    public function markAllAsRead(Request $request)
    {
        Notifikasi::where('user_id', $request->user()->id)
            ->update(['is_read' => 1]);

        return response()->json([
            'success' => true,
            'message' => 'Semua notifikasi sudah dibaca',
        ]);
    }
}
