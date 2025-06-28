<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Services\RekomendasiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
            'memasak' => 'Memasak',
            'berkebun' => 'Berkebun'
        ];

        return view('siswa.profil.index', compact('user', 'profilCheck', 'minat_options'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'nis' => 'nullable|string|unique:users,nis,' . $user->id,
            'telepon' => 'nullable|string|max:15',
            'alamat' => 'nullable|string',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'required|date|before:today',
            'nilai_rata_rata' => 'required|numeric|min:0|max:100',
            'minat' => 'required|array|min:1',
            'minat.*' => 'string',
            'prestasi' => 'nullable|string',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $data = $request->except(['password', 'password_confirmation']);
        $data['minat'] = json_encode($request->minat);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        // Regenerate rekomendasi jika profil sudah lengkap
        $profilCheck = $this->rekomendasiService->cekKelengkapanProfil($user->fresh());
        if ($profilCheck['lengkap'] && !$user->sudahTerdaftarEkstrakurikuler()) {
            $this->rekomendasiService->generateRekomendasi($user->fresh());
        }

        return redirect()->route('siswa.profil')
            ->with('success', 'Profil berhasil diperbarui!');
    }
}
