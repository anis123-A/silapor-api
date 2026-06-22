<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfilController extends Controller
{
    // GET /api/profil
    public function show(Request $request)
    {
        return response()->json([
            'success' => true,
            'user'    => $request->user()->load('fakultas'),
        ]);
    }

    // POST /api/profil/update
    public function update(Request $request)
    {
        $request->validate([
            'nama'        => 'sometimes|string|max:150',
            'prodi'       => 'sometimes|string|max:100',
            'foto_profil' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = $request->user();
        $data = $request->only(['nama', 'prodi']);

        if ($request->hasFile('foto_profil')) {
            $path = $request->file('foto_profil')->store('profil', 'public');
            $data['foto_profil'] = Storage::url($path);
        }

        $user->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui',
            'user'    => $user->load('fakultas'),
        ]);
    }
}
