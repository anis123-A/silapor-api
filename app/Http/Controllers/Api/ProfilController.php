<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Prodi;

class ProfilController extends Controller
{
    // GET /api/profil
    public function show(Request $request)
{
    return response()->json([
        'success' => true,
        'user'    => $request->user()->load(['fakultas', 'prodi']),
    ]);
}

    // POST /api/profil/update
    public function update(Request $request)
{
    $request->validate([
        'nama'        => 'sometimes|string|max:150',
        'prodi_id'    => 'sometimes|exists:prodis,id',
        'foto_profil' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    $user = $request->user();
    $data = $request->only(['nama', 'prodi_id']);

    if ($request->hasFile('foto_profil')) {
        $path = $request->file('foto_profil')->store('profil', 'public');
        $data['foto_profil'] = Storage::url($path);
    }

    $user->update($data);

    return response()->json([
        'success' => true,
        'message' => 'Profil berhasil diperbarui',
        'user'    => $user->load(['fakultas', 'prodi']),
    ]);
}

    //Ganti password
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8',
        ]);

        $user = Auth::user();

        // Cek password lama
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Kata sandi lama salah!'
            ], 400);
        }

        // Update password baru
        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kata sandi berhasil diperbarui!'
        ], 200);
    }

    //ambil daftar prodi berdasarkan fakultas
    public function getProdiByFakultas(Request $request)
{
    // Mengambil fakultas_id dari query param /api/prodi?fakultas_id=...
    $fakultasId = $request->query('fakultas_id');

    if ($fakultasId) {
        $prodi = Prodi::where('fakultas_id', $fakultasId)->get();
    } else {
        $prodi = Prodi::all();
    }

    return response()->json([
        'success' => true,
        'prodi' => $prodi
    ], 200);
  }
}
