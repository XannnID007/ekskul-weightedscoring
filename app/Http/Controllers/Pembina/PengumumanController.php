<?php

namespace App\Http\Controllers\Pembina;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengumumanController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $ekstrakurikulerIds = $user->ekstrakurikulerSebagaiPembina()->pluck('id');

        $pengumumans = Pengumuman::with('ekstrakurikuler')
            ->whereIn('ekstrakurikuler_id', $ekstrakurikulerIds)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('pembina.pengumuman.index', compact('pengumumans'));
    }

    public function create()
    {
        $user = Auth::user();
        $ekstrakurikulers = $user->ekstrakurikulerSebagaiPembina;

        return view('pembina.pengumuman.create', compact('ekstrakurikulers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
            'ekstrakurikuler_id' => 'required|exists:ekstrakurikulers,id',
            'is_penting' => 'boolean'
        ]);

        $user = Auth::user();

        // Pastikan ekstrakurikuler milik pembina
        $ekstrakurikuler = $user->ekstrakurikulerSebagaiPembina()
            ->where('id', $request->ekstrakurikuler_id)
            ->first();

        if (!$ekstrakurikuler) {
            abort(403, 'Unauthorized action.');
        }

        Pengumuman::create([
            'judul' => $request->judul,
            'konten' => $request->konten,
            'ekstrakurikuler_id' => $request->ekstrakurikuler_id,
            'dibuat_oleh' => $user->id,
            'is_penting' => $request->boolean('is_penting')
        ]);

        return redirect()->route('pembina.pengumuman.index')
            ->with('success', 'Pengumuman berhasil dibuat!');
    }

    public function show(Pengumuman $pengumuman)
    {
        // Pastikan pengumuman milik ekstrakurikuler yang dibina user
        if ($pengumuman->ekstrakurikuler->pembina_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $pengumuman->load(['ekstrakurikuler', 'pembuat']);
        return view('pembina.pengumuman.show', compact('pengumuman'));
    }

    public function edit(Pengumuman $pengumuman)
    {
        // Pastikan pengumuman milik ekstrakurikuler yang dibina user
        if ($pengumuman->ekstrakurikuler->pembina_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $user = Auth::user();
        $ekstrakurikulers = $user->ekstrakurikulerSebagaiPembina;

        return view('pembina.pengumuman.edit', compact('pengumuman', 'ekstrakurikulers'));
    }

    public function update(Request $request, Pengumuman $pengumuman)
    {
        // Pastikan pengumuman milik ekstrakurikuler yang dibina user
        if ($pengumuman->ekstrakurikuler->pembina_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
            'ekstrakurikuler_id' => 'required|exists:ekstrakurikulers,id',
            'is_penting' => 'boolean'
        ]);

        $user = Auth::user();

        // Pastikan ekstrakurikuler milik pembina
        $ekstrakurikuler = $user->ekstrakurikulerSebagaiPembina()
            ->where('id', $request->ekstrakurikuler_id)
            ->first();

        if (!$ekstrakurikuler) {
            abort(403, 'Unauthorized action.');
        }

        $pengumuman->update([
            'judul' => $request->judul,
            'konten' => $request->konten,
            'ekstrakurikuler_id' => $request->ekstrakurikuler_id,
            'is_penting' => $request->boolean('is_penting')
        ]);

        return redirect()->route('pembina.pengumuman.index')
            ->with('success', 'Pengumuman berhasil diperbarui!');
    }

    public function destroy(Pengumuman $pengumuman)
    {
        // Pastikan pengumuman milik ekstrakurikuler yang dibina user
        if ($pengumuman->ekstrakurikuler->pembina_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $pengumuman->delete();

        return redirect()->route('pembina.pengumuman.index')
            ->with('success', 'Pengumuman berhasil dihapus!');
    }
}
