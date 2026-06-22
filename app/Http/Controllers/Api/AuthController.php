<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Fakultas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // ── REGISTER ──────────────────────────────────────────────
    // POST /api/register
    // Body: nama, nim, email, password, password_confirmation, fakultas_id, prodi
    public function register(Request $request)
    {
        $request->validate([
            'nama'                  => 'required|string|max:150',
            'nim'                   => 'required|string|max:20|unique:users,nim',
            'email'                 => 'required|email|unique:users,email',
            'password'              => 'required|string|min:8|confirmed',
            'fakultas_id'           => 'required|exists:fakultas,id',
            'prodi'                 => 'required|string|max:100',
        ]);

        $user = User::create([
            'nama'        => $request->nama,
            'nim'         => $request->nim,
            'email'       => $request->email,
            'password'    => Hash::make($request->password),
            'role'        => 'mahasiswa',
            'fakultas_id' => $request->fakultas_id,
            'prodi'       => $request->prodi,
        ]);

        $token = $user->createToken('silapor-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Akun berhasil dibuat',
            'token'   => $token,
            'user'    => $user->load('fakultas'),
        ], 201);
    }

    // ── LOGIN ─────────────────────────────────────────────────
    // POST /api/login
    // Body: email, password
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau password salah',
            ], 401);
        }

        if (!$user->is_aktif) {
            return response()->json([
                'success' => false,
                'message' => 'Akun Anda tidak aktif',
            ], 403);
        }

        // Hapus token lama, buat token baru
        $user->tokens()->delete();
        $token = $user->createToken('silapor-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
            'token'   => $token,
            'user'    => $user->load('fakultas'),
        ]);
    }

    // ── LOGOUT ────────────────────────────────────────────────
    // POST /api/logout
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil',
        ]);
    }

    // ── CEK USER YANG SEDANG LOGIN ────────────────────────────
    // GET /api/me
    public function me(Request $request)
    {
        return response()->json([
            'success' => true,
            'user'    => $request->user()->load('fakultas'),
        ]);
    }

    // ── DAFTAR FAKULTAS ───────────────────────────────────────
    // GET /api/fakultas
    public function getFakultas()
    {
        return response()->json([
            'success'  => true,
            'fakultas' => Fakultas::all(),
        ]);
    }
}
