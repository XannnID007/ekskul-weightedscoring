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

        // ✅ VALIDASI UTAMA: Cek apakah sudah punya pendaftaran (termasuk pending)
        $existingPendaftaran = $user->pendaftarans()->count();
        if ($existingPendaftaran > 0) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah memiliki pendaftaran ekstrakurikuler. Setiap siswa hanya dapat mendaftar satu ekstrakurikuler.'
                ], 400);
            }
            return redirect()->back()
                ->with('error', 'Anda sudah memiliki pendaftaran ekstrakurikuler. Setiap siswa hanya dapat mendaftar satu ekstrakurikuler.');
        }

        // ✅ VALIDASI TAMBAHAN: Cek apakah sudah terdaftar (status disetujui)
        if ($user->sudahTerdaftarEkstrakurikuler()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah terdaftar pada ekstrakurikuler lain. Setiap siswa hanya dapat mengikuti satu ekstrakurikuler.'
                ], 400);
            }
            return redirect()->back()
                ->with('error', 'Anda sudah terdaftar pada ekstrakurikuler lain. Setiap siswa hanya dapat mengikuti satu ekstrakurikuler.');
        }

        // ✅ VALIDASI SPESIFIK: Cek pendaftaran pada ekstrakurikuler yang sama
        if ($user->pendaftarans()->where('ekstrakurikuler_id', $ekstrakurikuler->id)->exists()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah pernah mendaftar pada ekstrakurikuler ini.'
                ], 400);
            }
            return redirect()->back()
                ->with('error', 'Anda sudah pernah mendaftar pada ekstrakurikuler ini.');
        }

        // ✅ VALIDASI KAPASITAS
        if (!$ekstrakurikuler->masihBisaDaftar()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ekstrakurikuler ini sudah penuh atau tidak aktif.'
                ], 400);
            }
            return redirect()->back()
                ->with('error', 'Ekstrakurikuler ini sudah penuh atau tidak aktif.');
        }

        // ✅ VALIDASI NILAI MINIMAL
        if ($user->nilai_rata_rata && $user->nilai_rata_rata < $ekstrakurikuler->nilai_minimal) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nilai rata-rata Anda belum memenuhi syarat minimal untuk ekstrakurikuler ini.'
                ], 400);
            }
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

        // ✅ DOUBLE CHECK: Sekali lagi cek sebelum insert
        $doubleCheck = $user->pendaftarans()->count();
        if ($doubleCheck > 0) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat memproses pendaftaran. Anda sudah memiliki pendaftaran ekstrakurikuler lain.'
                ], 400);
            }
            return redirect()->back()
                ->with('error', 'Tidak dapat memproses pendaftaran. Anda sudah memiliki pendaftaran ekstrakurikuler lain.');
        }

        try {
            Pendaftaran::create([
                'user_id' => $user->id,
                'ekstrakurikuler_id' => $ekstrakurikuler->id,
                'motivasi' => $request->motivasi,
                'pengalaman' => $request->pengalaman,
                'harapan' => $request->harapan,
                'tingkat_komitmen' => $request->tingkat_komitmen,
                'status' => 'pending'
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pendaftaran berhasil dikirim! Menunggu persetujuan dari pembina.'
                ]);
            }

            return redirect()->route('siswa.pendaftaran')
                ->with('success', 'Pendaftaran berhasil dikirim! Menunggu persetujuan dari pembina.');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat memproses pendaftaran. Silakan coba lagi.'
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memproses pendaftaran. Silakan coba lagi.');
        }
    }
}
