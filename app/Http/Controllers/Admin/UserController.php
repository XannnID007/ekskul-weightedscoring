<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str; // <-- TAMBAHKAN INI
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\SiswaImport;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $role = $request->get('role', 'siswa');
        $users = User::where('role', $role)->paginate(10);

        return view('admin.user.index', compact('users', 'role'));
    }

    public function create(Request $request)
    {
        $role = $request->get('role', 'siswa');
        return view('admin.user.create', compact('role'));
    }

    // =================================================================
    // KODE DI BAWAH INI TELAH DIUBAH
    // =================================================================
    public function store(Request $request)
    {
        // Cek jika role yang dibuat adalah "siswa"
        if ($request->role === 'siswa') {

            // --- LOGIKA BARU UNTUK MEMBUAT AKUN BAYANGAN SISWA ---
            $request->validate([
                'name' => 'required|string|max:255',
                'nis' => 'required|string|unique:users,nis', // Pastikan NISN unik
                'jenis_kelamin' => 'required|in:L,P',
            ]);

            User::create([
                'name' => $request->name,
                'nis' => $request->nis,
                'jenis_kelamin' => $request->jenis_kelamin,
                'role' => 'siswa',

                // Buat email dan password DUMMY yang tidak bisa dipakai login
                'email' => $request->nis . '@placeholder.school',
                'password' => Hash::make(Str::random(40)),
                'email_verified_at' => null, // Akun belum aktif
            ]);
        } else {

            // --- LOGIKA LAMA UNTUK ADMIN DAN PEMBINA ---
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users',
                'password' => 'required|string|min:6',
                'role' => 'required|in:admin,pembina',
                'telepon' => 'nullable|string',
            ]);

            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'telepon' => $request->telepon,
                'email_verified_at' => now(), // Akun langsung aktif
            ]);
        }

        return redirect()->route('admin.user.index', ['role' => $request->role])
            ->with('success', 'User berhasil ditambahkan!');
    }
    // =================================================================
    // AKHIR DARI BAGIAN YANG DIUBAH
    // =================================================================

    public function show(User $user)
    {
        return view('admin.user.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('admin.user.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        // Cek jika user yang di-update adalah "siswa"
        if ($user->role === 'siswa') {

            // --- LOGIKA BARU UNTUK UPDATE DATA POKOK SISWA ---
            $request->validate([
                'name' => 'required|string|max:255',
                // Pastikan NISN unik, kecuali untuk user ini sendiri
                'nis' => 'required|string|unique:users,nis,' . $user->id,
                'jenis_kelamin' => 'required|in:L,P',
            ]);

            // Update hanya data pokok, JANGAN sentuh email/password
            $user->update([
                'name' => $request->name,
                'nis' => $request->nis,
                'jenis_kelamin' => $request->jenis_kelamin,
            ]);
        } else {

            // --- LOGIKA LAMA UNTUK ADMIN DAN PEMBINA ---
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'password' => 'nullable|string|min:6',
                'telepon' => 'nullable|string',
            ]);

            $data = $request->except('password');

            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            $user->update($data);
        }

        return redirect()->route('admin.user.index', ['role' => $user->role])
            ->with('success', 'User berhasil diperbarui!');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('admin.user.index', ['role' => $user->role])
            ->with('success', 'User berhasil dihapus!');
    }

    public function importSiswa(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        try {
            Excel::import(new SiswaImport, $request->file('file'));

            return redirect()->route('admin.user.index', ['role' => 'siswa'])
                ->with('success', 'Data siswa berhasil diimport!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal import data: ' . $e->getMessage());
        }
    }
}
