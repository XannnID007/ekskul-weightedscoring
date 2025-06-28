<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Ekstrakurikuler;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EkstrakurikulerController extends Controller
{
    public function index(Request $request)
    {
        $query = Ekstrakurikuler::with(['pembina', 'galeris'])
            ->aktif()
            ->withCount(['pendaftarans as total_pendaftar']);

        // Filter berdasarkan kategori
        if ($request->filled('kategori')) {
            $query->whereJsonContains('kategori', $request->kategori);
        }

        // Filter berdasarkan pencarian
        if ($request->filled('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%')
                ->orWhere('deskripsi', 'like', '%' . $request->search . '%');
        }

        // Filter berdasarkan ketersediaan
        if ($request->filled('tersedia')) {
            $query->tersedia();
        }

        $ekstrakurikulers = $query->paginate(12);

        $kategori_options = [
            'olahraga' => 'Olahraga',
            'seni' => 'Seni',
            'akademik' => 'Akademik',
            'teknologi' => 'Teknologi',
            'bahasa' => 'Bahasa',
            'kepemimpinan' => 'Kepemimpinan',
            'budaya' => 'Budaya',
            'media' => 'Media'
        ];

        return view('siswa.ekstrakurikuler.index', compact('ekstrakurikulers', 'kategori_options'));
    }

    public function show(Ekstrakurikuler $ekstrakurikuler)
    {
        $ekstrakurikuler->load(['pembina', 'galeris', 'pengumumans' => function ($query) {
            $query->latest()->limit(5);
        }]);

        $user = Auth::user();
        $sudahDaftar = $user->pendaftarans()
            ->where('ekstrakurikuler_id', $ekstrakurikuler->id)
            ->exists();

        $sudahTerdaftarLain = $user->sudahTerdaftarEkstrakurikuler();

        return view('siswa.ekstrakurikuler.show', compact('ekstrakurikuler', 'sudahDaftar', 'sudahTerdaftarLain'));
    }

    public function daftar(Request $request, Ekstrakurikuler $ekstrakurikuler)
    {
        $user = Auth::user();

        // Validasi apakah sudah terdaftar ekstrakurikuler lain
        if ($user->sudahTerdaftarEkstrakurikuler()) {
            return redirect()->back()
                ->with('error', 'Anda sudah terdaftar pada ekstrakurikuler lain. Setiap siswa hanya dapat mengikuti satu ekstrakurikuler.');
        }

        // Validasi apakah sudah pernah mendaftar
        if ($user->pendaftarans()->where('ekstrakurikuler_id', $ekstrakurikuler->id)->exists()) {
            return redirect()->back()
                ->with('error', 'Anda sudah pernah mendaftar pada ekstrakurikuler ini.');
        }

        // Validasi kapasitas
        if (!$ekstrakurikuler->masihBisaDaftar()) {
            return redirect()->back()
                ->with('error', 'Ekstrakurikuler ini sudah penuh atau tidak aktif.');
        }

        // Validasi nilai minimal
        if ($user->nilai_rata_rata < $ekstrakurikuler->nilai_minimal) {
            return redirect()->back()
                ->with('error', 'Nilai rata-rata Anda belum memenuhi syarat minimal untuk ekstrakurikuler ini.');
        }

        $request->validate([
            'motivasi' => 'required|string|min:50',
            'pengalaman' => 'nullable|string',
            'harapan' => 'required|string|min:20',
            'tingkat_komitmen' => 'required|in:tinggi,sedang,rendah',
            'konfirmasi' => 'required|accepted'
        ]);

        Pendaftaran::create([
            'user_id' => $user->id,
            'ekstrakurikuler_id' => $ekstrakurikuler->id,
            'motivasi' => $request->motivasi,
            'pengalaman' => $request->pengalaman,
            'harapan' => $request->harapan,
            'tingkat_komitmen' => $request->tingkat_komitmen,
            'status' => 'pending'
        ]);

        return redirect()->route('siswa.pendaftaran')
            ->with('success', 'Pendaftaran berhasil dikirim! Menunggu persetujuan dari pembina.');
    }
}
