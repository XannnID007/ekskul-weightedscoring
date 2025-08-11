<?php

namespace App\Http\Controllers\Pembina;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SiswaController extends Controller
{
    public function index()
    {
        $pembina = Auth::user();
        $ekstrakurikulers = $pembina->ekstrakurikulerSebagaiPembina()
            ->with(['siswaDisetujui' => function ($query) {
                $query->orderBy('name', 'asc');
            }])
            ->get();

        return view('pembina.siswa.index', compact('ekstrakurikulers'));
    }
}
