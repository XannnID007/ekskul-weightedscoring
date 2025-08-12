<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,pembina,siswa',
            'nis' => 'nullable|string|unique:users',
            'telepon' => 'nullable|string',
            'jenis_kelamin' => 'nullable|in:L,P',
        ]);

        $data = $request->all();
        $data['password'] = Hash::make($request->password);
        $data['email_verified_at'] = now();

        User::create($data);

        return redirect()->route('admin.user.index', ['role' => $request->role])
            ->with('success', 'User berhasil ditambahkan!');
    }

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
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'nis' => 'nullable|string|unique:users,nis,' . $user->id,
            'telepon' => 'nullable|string',
            'jenis_kelamin' => 'nullable|in:L,P',
        ]);

        $data = $request->except('password');

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

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
