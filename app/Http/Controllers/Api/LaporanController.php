<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FotoLaporan;
use App\Models\Laporan;
use App\Models\Notifikasi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class LaporanController extends Controller
{
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

        $stats = [
            'total' => $laporan->count(),
            Laporan::STATUS_MENUNGGU => $laporan->where('status', Laporan::STATUS_MENUNGGU)->count(),
            Laporan::STATUS_DIPROSES => $laporan->where('status', Laporan::STATUS_DIPROSES)->count(),
            Laporan::STATUS_SELESAI => $laporan->where('status', Laporan::STATUS_SELESAI)->count(),
            Laporan::STATUS_DITOLAK => $laporan->where('status', Laporan::STATUS_DITOLAK)->count(),
        ];

        return response()->json([
            'success' => true,
            'stats' => $stats,
            'laporan' => $laporan,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:200',
            'deskripsi' => 'required|string',
            'lokasi' => 'required|string|max:200',
            'kategori_id' => 'required|exists:kategori,id',
            'foto' => 'nullable|array|max:5',
            'foto.*' => 'image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $laporan = Laporan::create([
            'user_id' => $request->user()->id,
            'kategori_id' => $request->kategori_id,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'lokasi' => $request->lokasi,
            'status' => Laporan::STATUS_MENUNGGU,
        ]);

        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $file) {
                $path = $file->store('laporan/' . $laporan->id, 'public');

                FotoLaporan::create([
                    'laporan_id' => $laporan->id,
                    'url_foto' => Storage::url($path),
                ]);
            }
        }

        $admins = User::where('role', User::ROLE_ADMIN)->get();
        foreach ($admins as $admin) {
            Notifikasi::create([
                'user_id' => $admin->id,
                'laporan_id' => $laporan->id,
                'judul' => 'Laporan Baru Masuk',
                'pesan' => 'Ada laporan baru: ' . $laporan->judul . ' dari ' . $request->user()->nama,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Laporan berhasil dikirim',
            'laporan' => $laporan->load(['kategori', 'foto']),
        ], 201);
    }

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

    public function update(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required|string|max:200',
            'deskripsi' => 'required|string',
            'lokasi' => 'required|string|max:200',
            'kategori_id' => 'required|exists:kategori,id',
            'foto' => 'nullable|array|max:4',
            'foto.*' => 'image|mimes:jpg,jpeg,png|max:2048',
            'photos' => 'nullable|array|max:4',
            'photos.*' => 'image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $laporan = Laporan::with('foto')->findOrFail($id);

        Gate::authorize('update', $laporan);

        // Guard status: laporan hanya bisa diedit saat masih menunggu.
        if ($laporan->status !== Laporan::STATUS_MENUNGGU) {
            return response()->json([
                'success' => false,
                'message' => 'Laporan tidak bisa diubah/dihapus karena sudah diproses.',
            ], 403);
        }

        $uploadedPhotos = array_merge(
            $this->normalizeUploadedFiles($request->file('foto', [])),
            $this->normalizeUploadedFiles($request->file('photos', [])),
        );

        // Guard foto: validasi total foto sebelum file disimpan ke storage.
        if (count($uploadedPhotos) > 0) {
            $existingPhotoCount = $laporan->foto()->count();
            $remainingSlots = max(0, 4 - $existingPhotoCount);

            if (($existingPhotoCount + count($uploadedPhotos)) > 4) {
                return response()->json([
                    'success' => false,
                    'message' => "Maksimal 4 foto per laporan. Anda hanya bisa menambahkan {$remainingSlots} foto lagi.",
                ], 422);
            }
        }

        $laporan->update([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'lokasi' => $request->lokasi,
            'kategori_id' => $request->kategori_id,
        ]);

        try {
            foreach ($uploadedPhotos as $file) {
                if (!$file) {
                    continue;
                }

                $path = $file->store('laporan/' . $laporan->id, 'public');
                FotoLaporan::create([
                    'laporan_id' => $laporan->id,
                    'url_foto' => Storage::url($path),
                ]);
            }
        } catch (Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan foto laporan.',
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Laporan berhasil diperbarui.',
            'laporan' => $laporan->fresh(['kategori', 'foto']),
        ]);
    }

    public function destroy($id)
    {
        $laporan = Laporan::with('foto')->findOrFail($id);

        Gate::authorize('delete', $laporan);

        // Guard status: laporan hanya bisa dihapus saat masih menunggu.
        if ($laporan->status !== Laporan::STATUS_MENUNGGU) {
            return response()->json([
                'success' => false,
                'message' => 'Laporan tidak bisa diubah/dihapus karena sudah diproses.',
            ], 403);
        }

        foreach ($laporan->foto as $foto) {
            $this->deletePublicFile($foto->url_foto);
            $foto->delete();
        }

        $laporan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Laporan berhasil dihapus',
        ]);
    }

    private function deletePublicFile(?string $url): void
    {
        if (!$url) {
            return;
        }

        $path = parse_url($url, PHP_URL_PATH) ?: $url;
        $path = ltrim($path, '/');

        if (Str::startsWith($path, 'storage/')) {
            $path = Str::after($path, 'storage/');
        }

        Storage::disk('public')->delete($path);
    }

    private function normalizeUploadedFiles($files): array
    {
        if (!$files) {
            return [];
        }

        return is_array($files) ? $files : [$files];
    }
}
