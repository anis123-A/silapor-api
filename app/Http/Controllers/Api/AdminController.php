<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use App\Models\User;
use App\Models\Notifikasi;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // GET /api/admin/dashboard
    public function dashboard()
    {
        return response()->json([
            'success' => true,
            'stats'   => [
                'total'      => Laporan::count(),
                'menunggu'   => Laporan::where('status', 'menunggu')->count(),
                'diproses'   => Laporan::where('status', 'diproses')->count(),
                'selesai'    => Laporan::where('status', 'selesai')->count(),
                'ditolak'    => Laporan::where('status', 'ditolak')->count(),
                'total_user' => User::where('role', 'mahasiswa')->count(),
            ],
            'laporan_terbaru' => Laporan::with(['kategori', 'user', 'foto'])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get(),
        ]);
    }

    // GET /api/admin/laporan
    public function indexLaporan(Request $request)
    {
        $query = Laporan::with(['kategori', 'user', 'foto'])
            ->orderBy('created_at', 'desc');

        if ($request->status)      $query->where('status', $request->status);
        if ($request->kategori_id) $query->where('kategori_id', $request->kategori_id);
        if ($request->search)      $query->where('judul', 'like', '%' . $request->search . '%');

        return response()->json([
            'success' => true,
            'laporan' => $query->paginate(10),
        ]);
    }

    // GET /api/admin/laporan/{id}
    public function showLaporan($id)
    {
        $laporan = Laporan::with(['kategori', 'user.fakultas', 'foto'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'laporan' => $laporan,
        ]);
    }

    // POST /api/admin/laporan/{id}/status
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status'        => 'required|in:diproses,selesai,ditolak',
            'catatan_admin' => 'nullable|string|max:500',
        ]);

        $laporan = Laporan::with('user')->findOrFail($id);

        // Status final tidak boleh diubah lagi
        if (in_array($laporan->status, ['selesai', 'ditolak'])) {
            return response()->json([
                'success' => false,
                'message' => 'Status laporan sudah final dan tidak dapat diubah lagi.',
            ], 422);
        }
        $laporan->update([
            'status'        => $request->status,
            'catatan_admin' => $request->catatan_admin,
        ]);

        $pesanMap = [
            'diproses' => 'Laporan Anda "' . $laporan->judul . '" sedang diproses oleh tim sarana prasarana.',
            'selesai'  => 'Laporan Anda "' . $laporan->judul . '" telah selesai ditangani. Terima kasih!',
            'ditolak'  => 'Laporan Anda "' . $laporan->judul . '" ditolak.' .
                          ($request->catatan_admin ? ' Alasan: ' . $request->catatan_admin : ''),
        ];

        Notifikasi::create([
            'user_id'    => $laporan->user_id,
            'laporan_id' => $laporan->id,
            'judul'      => 'Status Laporan Diperbarui',
            'pesan'      => $pesanMap[$request->status],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status laporan berhasil diperbarui',
            'laporan' => $laporan->fresh(['kategori', 'user', 'foto']),
        ]);
    }
}
