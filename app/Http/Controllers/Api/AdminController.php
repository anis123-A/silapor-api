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
                Laporan::STATUS_MENUNGGU => Laporan::where('status', Laporan::STATUS_MENUNGGU)->count(),
                Laporan::STATUS_DIPROSES => Laporan::where('status', Laporan::STATUS_DIPROSES)->count(),
                Laporan::STATUS_SELESAI  => Laporan::where('status', Laporan::STATUS_SELESAI)->count(),
                Laporan::STATUS_DITOLAK  => Laporan::where('status', Laporan::STATUS_DITOLAK)->count(),
                'total_user'             => User::where('role', User::ROLE_MAHASISWA)->count(),
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

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->kategori_id) {
            $query->where('kategori_id', $request->kategori_id);
        }

        if ($request->search) {
            $query->where('judul', 'like', '%' . $request->search . '%');
        }

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
            'status'        => 'required|in:' . implode(',', [
                Laporan::STATUS_DIPROSES,
                Laporan::STATUS_SELESAI,
                Laporan::STATUS_DITOLAK,
            ]),
            'catatan_admin' => 'nullable|string|max:500',
        ]);

        $laporan = Laporan::with('user')->findOrFail($id);

        // Status final tidak boleh diubah lagi
        if (in_array($laporan->status, [Laporan::STATUS_SELESAI, Laporan::STATUS_DITOLAK], true)) {
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
            Laporan::STATUS_DIPROSES => 'Laporan Anda "' . $laporan->judul . '" sedang diproses oleh tim sarana prasarana.',
            Laporan::STATUS_SELESAI  => 'Laporan Anda "' . $laporan->judul . '" telah selesai ditangani. Terima kasih!',
            Laporan::STATUS_DITOLAK  => 'Laporan Anda "' . $laporan->judul . '" ditolak.' .
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
