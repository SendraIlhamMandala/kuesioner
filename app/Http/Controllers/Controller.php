<?php

namespace App\Http\Controllers;

use App\Models\Kuesioner;
use App\Models\Mahasiswa;
use App\Models\Matakuliah;
use App\Models\Trkuesk;
use App\Models\Trkuesl;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;


    public function index() {
        $mahasiswa = Mahasiswa::where('nimhs', Auth()->user()->nimhs)->first();
        $trkuesl = Trkuesl::where('nimhs', Auth()->user()->nimhs)->get();
        $kuesioner = Kuesioner::all();
        $kelas_kuesioner = collect(array_unique($trkuesl->pluck('klkues')->toArray()));
        $kode_kuesioner = collect(array_unique($trkuesl->pluck('kdkues')->toArray()));

        
        $trkuesk = Trkuesk::where('nimhs', Auth()->user()->nimhs)->get();
        $matakuliah = Matakuliah::all();
        $kuesionerA = Kuesioner::where('klkues', 'A')->get();
        $kelas_matakuliah = collect(array_unique($trkuesk->pluck('kdkmk')->toArray()));
        // dd(Auth()->user()->nimhs,$trkuesk->where('kdkmk', $kelas_matakuliah->first()),$kelas_matakuliah->first());

        $kelas = [
            'A' => 'Perkuliahan',
            'B' => 'LAYANAN ADMINISTRASI AKADEMIK',
            'C' => 'LAYANAN KEMAHASISWAAN',
            'D' => 'LAYANAN PERPUSTAKAAN',
            'E' => 'LAYANAN SARANA PRASARANA',
            'F' => 'LAYANAN KEUANGAN'
        ];
    
        return view('dashboard', compact(
            'mahasiswa',
            'trkuesl', 
            'kuesioner', 
            'kelas_kuesioner',
            'kode_kuesioner',
            'kelas',
            'trkuesk', 
            'matakuliah',
            'kelas_matakuliah',
            'kuesionerA',
        
        
        ));
        }

    public function show ($nimhs) {
        $mahasiswa = Mahasiswa::where('nimhs', $nimhs)->first();
        $trkuesl = Trkuesl::where('nimhs', $nimhs)->get();
        $kuesioner = Kuesioner::all();
        $kelas_kuesioner = collect(array_unique($trkuesl->pluck('klkues')->toArray()));
        $kode_kuesioner = collect(array_unique($trkuesl->pluck('kdkues')->toArray()));
        $kelas = [
            'A' => 'Perkuliahan',
            'B' => 'LAYANAN ADMINISTRASI AKADEMIK',
            'C' => 'LAYANAN KEMAHASISWAAN',
            'D' => 'LAYANAN PERPUSTAKAAN',
            'E' => 'LAYANAN SARANA PRASARANA',
            'F' => 'LAYANAN KEUANGAN'
        ];
    
        return view('mahasiswaView', compact(
            'mahasiswa',
            'trkuesl', 
            'kuesioner', 
            'kelas_kuesioner',
            'kode_kuesioner',
            'kelas'));
    }


    public function showsk ($nimhs) {
        $mahasiswa = Mahasiswa::where('nimhs', $nimhs)->first();
        $trkuesk = Trkuesk::where('nimhs', $nimhs)->get();
        $matakuliah = Matakuliah::all();
        $kuesioner = Kuesioner::where('klkues', 'A')->get();
        
        $kelas_matakuliah = collect(array_unique($trkuesk->pluck('kdkmk')->toArray()));
        // dd($kelas_matakuliah);
        // $kode_matakuliah = collect(array_unique($trkuesk->pluck('kdkues')->toArray()));
  
        return view('mahasiswaViewSk', compact(
            'mahasiswa',
            'trkuesk', 
            'matakuliah',
            'kelas_matakuliah',
            'kuesioner',
        ));
    }
}
