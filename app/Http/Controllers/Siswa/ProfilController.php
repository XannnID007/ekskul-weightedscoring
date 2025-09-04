<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Services\RekomendasiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User; // Pastikan model User di-import

class ProfilController extends Controller
{
    protected $rekomendasiService;

    public function __construct(RekomendasiService $rekomendasiService)
    {
        $this->rekomendasiService = $rekomendasiService;
    }

    public function index()
    {
        $user = Auth::user();
        $profilCheck = $this->rekomendasiService->cekKelengkapanProfil($user);

        // Daftar minat tidak perlu diubah
        $minat_options = [
            'olahraga' => 'Olahraga',
            'seni' => 'Seni & Budaya',
            'akademik' => 'Akademik',
            'teknologi' => 'Teknologi',
            'bahasa' => 'Bahasa',
            'kepemimpinan' => 'Kepemimpinan',
            'sosial' => 'Sosial',
            'musik' => 'Musik',
            'tari' => 'Tari',
            'teater' => 'Teater',
            'jurnalistik' => 'Jurnalistik',
            'fotografi' => 'Fotografi',
        ];

        // Ganti route 'siswa.profil.index' jika Anda menggunakan nama route yang berbeda
        return view('siswa.profil.index', compact('user', 'profilCheck', 'minat_options'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        // --- PERUBAHAN UTAMA DI BAGIAN VALIDASI ---
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'nis' => 'nullable|string|unique:users,nis,' . $user->id,
            'telepon' => 'nullable|string|max:15',
            'alamat' => 'nullable|string',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'required|date|before:today',
            'nilai_rata_rata' => 'required|numeric|min:0|max:100',
            // Tambahkan 'max:3' untuk membatasi pilihan
            'minat' => 'required|array|min:1|max:3',
            'minat.*' => 'string',
            'jadwal_luang' => 'nullable|array',
            'jadwal_luang.*' => 'string',
            'prestasi' => 'nullable|string',
            'password' => 'nullable|string|min:8|confirmed', // Lebih baik min:8 untuk keamanan
        ], [
            // Tambahkan pesan error custom untuk batasan minat
            'minat.max' => 'Anda hanya dapat memilih maksimal 3 minat.',
            'minat.required' => 'Anda harus memilih minimal 1 minat.',
        ]);

        // --- LOGIKA PENYIMPANAN DATA DIPERBARUI AGAR LEBIH JELAS ---
        $user->name = $request->name;
        $user->email = $request->email;
        $user->nis = $request->nis;
        $user->telepon = $request->telepon;
        $user->alamat = $request->alamat;
        $user->jenis_kelamin = $request->jenis_kelamin;
        $user->tanggal_lahir = $request->tanggal_lahir;
        $user->nilai_rata_rata = $request->nilai_rata_rata;
        $user->prestasi = $request->prestasi;

        // Simpan minat dan jadwal (Laravel akan handle encode/decode via $casts di Model)
        $user->minat = $request->minat ?? [];
        $user->jadwal_luang = $request->jadwal_luang ?? [];

        // Update password hanya jika diisi
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save(); // Simpan semua perubahan

        // ... (Bagian Regenerate Rekomendasi tidak perlu diubah) ...
        $profilCheck = $this->rekomendasiService->cekKelengkapanProfil($user->fresh());
        if ($profilCheck['lengkap'] && !$user->sudahTerdaftarEkstrakurikuler()) {
            $this->rekomendasiService->generateRekomendasi($user->fresh());
        }

        return redirect()->route('siswa.profil')
            ->with('success', 'Profil berhasil diperbarui!');
    }
}
