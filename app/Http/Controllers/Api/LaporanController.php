<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use App\Models\FotoLaporan;
use App\Models\Notifikasi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LaporanController extends Controller
{
    // ── DAFTAR LAPORAN MILIK MAHASISWA ────────────────────────
    // GET /api/laporan
    // Query opsional: ?status=menunggu&kategori_id=1
    public function index(Request $request)
    {
        $query = Laporan::with(['kategori', 'foto'])
            ->where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc');

        if ($request->status) {
            $query->where('status', $request->status);
        }
        if ($request->kategori_id) {
            $query->where('kategori_id', $request->kategori_id);
        }

        $laporan = $query->get();

        // Hitung statistik
        $stats = [
            'total'     => $laporan->count(),
            Laporan::STATUS_MENUNGGU => $laporan->where('status', Laporan::STATUS_MENUNGGU)->count(),
            Laporan::STATUS_DIPROSES => $laporan->where('status', Laporan::STATUS_DIPROSES)->count(),
            Laporan::STATUS_SELESAI  => $laporan->where('status', Laporan::STATUS_SELESAI)->count(),
            Laporan::STATUS_DITOLAK  => $laporan->where('status', Laporan::STATUS_DITOLAK)->count(),
        ];

        return response()->json([
            'success' => true,
            'stats'   => $stats,
            'laporan' => $laporan,
        ]);
    }

    // ── BUAT LAPORAN BARU ─────────────────────────────────────
    // POST /api/laporan
    // Body (form-data): judul, deskripsi, lokasi, kategori_id, foto[] (file)
    public function store(Request $request)
    {
        $request->validate([
            'judul'       => 'required|string|max:200',
            'deskripsi'   => 'required|string',
            'lokasi'      => 'required|string|max:200',
            'kategori_id' => 'required|exists:kategori,id',
            'foto'        => 'nullable|array|max:5',
            'foto.*'      => 'image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Simpan laporan
        $laporan = Laporan::create([
            'user_id'     => $request->user()->id,
            'kategori_id' => $request->kategori_id,
            'judul'       => $request->judul,
            'deskripsi'   => $request->deskripsi,
            'lokasi'      => $request->lokasi,
            'status'      => Laporan::STATUS_MENUNGGU,
        ]);

        // Upload foto (jika ada)
        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $file) {
                $path = $file->store('laporan/' . $laporan->id, 'public');
                FotoLaporan::create([
                    'laporan_id' => $laporan->id,
                    'url_foto'   => Storage::url($path),
                ]);
            }
        }

        // Kirim notifikasi ke semua admin
        $admins = User::where('role', User::ROLE_ADMIN)->get();
        foreach ($admins as $admin) {
            Notifikasi::create([
                'user_id'    => $admin->id,
                'laporan_id' => $laporan->id,
                'judul'      => 'Laporan Baru Masuk',
                'pesan'      => 'Ada laporan baru: ' . $laporan->judul .
                                ' dari ' . $request->user()->nama,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Laporan berhasil dikirim',
            'laporan' => $laporan->load(['kategori', 'foto']),
        ], 201);
    }

    // ── DETAIL LAPORAN ────────────────────────────────────────
    // GET /api/laporan/{id}
    public function show(Request $request, $id)
    {
        $laporan = Laporan::with(['kategori', 'foto', 'user'])
            ->where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$laporan) {
            return response()->json([
                'success' => false,
                'message' => 'Laporan tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'laporan' => $laporan,
        ]);
    }

    // ── HAPUS LAPORAN ─────────────────────────────────────────
    // DELETE /api/laporan/{id}
    public function destroy(Request $request, $id)
    {
        $laporan = Laporan::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$laporan) {
            return response()->json([
                'success' => false,
                'message' => 'Laporan tidak ditemukan',
            ], 404);
        }

        if ($laporan->status !== Laporan::STATUS_MENUNGGU) {
            return response()->json([
                'success' => false,
                'message' => 'Laporan yang sudah diproses tidak bisa dihapus',
            ], 403);
        }

        $laporan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Laporan berhasil dihapus',
        ]);
    }

    //edit laporan
    public function update(Request $request, $id)
    {
        $request->validate([
            'judul'       => 'required|string|max:200',
            'deskripsi'   => 'required|string',
            'lokasi'      => 'required|string|max:200',
            'kategori_id' => 'required|exists:kategori,id',
        ]);

        $laporan = Laporan::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$laporan) {
            return response()->json([
                'success' => false,
                'message' => 'Laporan tidak ditemukan',
            ], 404);
        }

        // Hanya laporan menunggu yang boleh diedit
        if ($laporan->status !== Laporan::STATUS_MENUNGGU) {
            return response()->json([
                'success' => false,
                'message' => 'Laporan yang sudah diproses tidak dapat diedit.',
            ], 403);
        }

        $laporan->update([
            'judul'       => $request->judul,
            'deskripsi'   => $request->deskripsi,
            'lokasi'      => $request->lokasi,
            'kategori_id' => $request->kategori_id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Laporan berhasil diperbarui.',
            'laporan' => $laporan->fresh(['kategori', 'foto']),
        ]);
    }
}
