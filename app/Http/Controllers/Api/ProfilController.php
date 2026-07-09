<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Prodi;
use App\Services\PasswordUpdater;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

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

    // Ganti password
    public function updatePassword(Request $request, PasswordUpdater $passwordUpdater)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8',
        ]);

        $passwordError = $passwordUpdater->update(
            Auth::user(),
            $request->current_password,
            $request->new_password
        );

        if ($passwordError) {
            return response()->json([
                'success' => false,
                'message' => $passwordError['message'],
            ], $passwordError['status']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Kata sandi berhasil diperbarui!',
        ], 200);
    }

    // Ambil daftar prodi berdasarkan fakultas
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
            'prodi'   => $prodi,
        ], 200);
    }
}
