<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Dosen;
use App\Models\Setting;
use App\Models\Tahunsemester;
use App\Models\Tbkues;
use App\Models\Tblmk;
use App\Models\Trakd;
use App\Models\Trkuesk;
use App\Models\Trkuliah;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;

class DosenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    // public function show(Dosen $dosen)
    // {
    //     //
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Dosen $dosen)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Dosen $dosen)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Dosen $dosen)
    {
        //
    }

    public function exportMatkulDosen($matkul, $dosen)
    {

        $dosen_real = Dosen::find($dosen);


        $thsms = Tahunsemester::where('status', 'aktif')->first();
        $thsms_id = $thsms->id;
        $thsms_active = $thsms->thsms;


        $trakd = Trakd::where('kdkmk', $matkul)->where('nmdosen', $dosen_real->nmdosen)->where('thsms', $thsms_active)->get();

        $trkueskuliah = collect($trakd)
            ->flatMap(function ($value_trakd) {
                return $value_trakd->trkuliahs;
            })
            ->flatMap(function ($value_trkuesk) {
                return $value_trkuesk->trkuesks->where('skor', '!=', 0);
            });

        $trkuliah = collect($trakd)
            ->flatMap(function ($value_trakd) {
                return $value_trakd->trkuliahs->pluck('nimhs');
            });

        dd($trakd, $trkueskuliah ,  $trkuliah);


        // $trkuliah = Trkuliah::first();
        // $trkuesk = $trkuliah->trkuesks;
        // $kelas = Trkuliah::first()->kelas;

        // dd($trakd, $trkuliah , $trkuesk , $kelas);

        $dosen = [$dosen_real->nmdosen];
        $namaDosen = '';
        foreach ($dosen as $key => $value) {
            $namaDosen .= '<div>' .  $value . ' </div>';
        }
        // dd($matkul, $dosen_real , $namaDosen , $trakd, $trkuliah);

        // dd($dosen, $namaDosen , $matkul);
        $namaMatkul = Tblmk::where('kdkmk', $matkul)->first()->nakmk;

        // $tblmk = Tblmk::all()->pluck('kdkmk')->toArray();

        // $trkuesk2 = Trkuesk::whereIn('kdkmk', $tblmk)->where('skor', '!=', 0)->pluck('kdkmk')->unique();



        $trkuesk = $trkueskuliah;
        $trkueskKdkues = $trkuesk->pluck('kdkues')->unique();
        $trkueskKdkues2['total'] = 0;
        $trkueskKdkues2['average'] = [];
        $trkueskKdkues2['sum'] = [];
        $trkueskKdkues2['count'] = [''];
        foreach ($trkueskKdkues as $key => $value) {
            // dump($value);
            $skor = $trkuesk->where('kdkues', $value)->pluck('skor')->toArray();
            $trkueskKdkues2['average'][$key] = ['keter' => Tbkues::where('kdkues', $value)->first()->keter, 'skor' => array_sum($skor) / count($skor)];
            $trkueskKdkues2['sum'][$key] = array_sum($skor);
            $trkueskKdkues2['count'][$key] = count($skor);

            $trkueskKdkues2['total'] = $trkueskKdkues2['total'] + $trkueskKdkues2['average'][$key]['skor'];
        }

        // dd($trkuesk,$trkueskKdkues2);



        // dd($trkuesk,$trkueskKdkues2,$trkueskKdkues);
        // $pdf = App::make('dompdf.wrapper');

        $tableRows = '';

        foreach ($trkueskKdkues2['average'] as $key => $value) {
            $index = $key + 1;
            $keterangan = $value['skor'] < 4 && $value['skor'] >= 3 ? 'Baik' : ($value['skor'] < 3 && $value['skor'] >= 2 ? 'Kurang' : ($value['skor'] < 2 && $value['skor'] >= 1 ? 'Sangat Kurang' : ($value['skor'] < 1 && $value['skor'] >= 0 ? 'Sangat Kurang' : 'Sangat Baik')));
            $tableRows .= '
            <tr style="height: 21px;">
            <td style="width: 8.28024%; height: 21px; text-align: center;"> ' . $index . '</td>
            <td style="width: 60.8281%; height: 21px; ">' . $value['keter'] . '</td>
            <td style="width: 8.9172%; height: 21px; text-align: center; ">' . round($value['skor'], 2) . '</td>
            <td style="width: 21.9745%; height: 21px; text-align: center;">' . $keterangan . '</td>
            </tr>';
        }

        $rataRata = count($trkueskKdkues2['average']) ? round($trkueskKdkues2['total'] / count($trkueskKdkues2['average']), 2) : 0;
        $keteranganRataRata = $rataRata < 4 && $rataRata >= 3 ? 'Baik' : ($rataRata < 3 && $rataRata >= 2 ? 'Kurang' : ($rataRata < 2 && $rataRata >= 1 ? 'Sangat Kurang' : ($rataRata < 1 && $rataRata >= 0 ? 'Sangat Kurang' : 'Sangat Baik')));



        $tableRowsComment = '';

        $comments = Comment::where('kdkmk', $matkul)->where('tahunsemester_id', $thsms_id)
        ->whereHas('user', function ($query) use ($trkuliah) {
            $query->select('id')->whereIn('nimhs', $trkuliah);
        })
        ->get();

        // dd($comments);

        foreach ($comments as $key => $value) {
            $index = $key + 1;
            $tableRowsComment .= '
            <tr style="height: 21px;">
            <td style="width: 8.28024%; height: 21px; text-align: center;"> ' . $index . '</td>
            <td style="width: 60.8281%; height: 21px; ">' . $value->comment . '</td>
            </tr>';
        }



        $htmlContent = '
            
                <html>
                <head>
                <style>
                
                body {
                    size: A4;
                    font-family: Arial, sans-serif;
                }
                    }
                </style>
                </head>
                <body>
                <table border="1" style="border-collapse: collapse; width: 100%; height: 61px;">
                <tbody>
                <tr style="height: 111px;">
                <td style="width: 11.3175%; height: 40px; text-align: center; " rowspan="4"><img alt="" width="70" height="70" src="' . Controller::image_logo_base64() . '" /></td>
                <td style="width: 22.0159%; height: 40px;" rowspan="4">
                <p><strong>FAKULTAS ILMU SOSIAL DAN </strong><br /><strong>ILMU POLITIK </strong><strong>UNIVERSITAS GARUT </strong></p>
                <p>Jl. Cimanuk No. 285-A, Garut</p>
                </td>
                <td style="width: 16.6667%; height: 40px; text-align: center;" rowspan="4">
                <p><strong>FORM </strong><strong>ADMINISTRASI </strong><strong>AKADEMIK (FAA)</strong></p>
                </td>
                <td style="width: 14.6607%; height: 10px;">No. Dok</td>
                <td style="width: 3.7396%; height: 10px; text-align: center;">:</td>
                <td style="width: 20.6043%; height: 10px;"></td>
    
                </tr>
                <tr style="height: 21px;">
                <td style="width: 14.6607%; height: 10px;">Tgl. Terbit</td>
                <td style="width: 3.7396%; height: 10px; text-align: center;">:</td>
                <td style="width: 20.6043%; height: 10px;"></td>
    
                </tr>
                <tr style="height: 21px;">
                <td style="width: 14.6607%; height: 10px;">No. Revisi</td>
                <td style="width: 3.7396%; height: 10px; text-align: center;">:</td>
                <td style="width: 20.6043%; height: 10px;"></td>
    
                </tr>
                <tr style="height: 21px;">
                <td style="width: 14.6607%; height: 10px;">Jumlah hal</td>
                <td style="width: 3.7396%; height: 10px; text-align: center;">:</td>
                <td style="width: 20.6043%; height: 10px;"></td>
    
                </tr>
                <tr style="height: 21px;">
                <td style="width: 89.0047%; height: 21px; text-align: center;" colspan="6"><strong>TINGKAT KEPUASAN MAHASISWA TERHADAP DOSEN DAN PERKULIAHAN</strong></td>
    
                </tr>
                </tbody>
                </table>
                <p></p>
                <table border="0" style="width: 100%; border-collapse: collapse;">
                <tbody>
                <tr>
                <td >Program studi</td>
                <td >:</td>
                <td >Ilmu Administrasi Negara</td>
                <td >Tahun Akademik</td>
                <td ">:</td>
                <td > ' . Setting::find(2)->is_open . '</td>
                </tr>
                <tr>
                <td >Mata Kuliah</td>
                <td >:</td>
                <td >' . $namaMatkul . ' </td>
                <td ></td>
                <td ></td>
                <td ></td>
                </tr>
                <tr>
                <td style="vertical-align: top; text-align: left;">Dosen</td>
                <td style="vertical-align: top; text-align: left;">:</td>
                <td colspan="2" >' . $namaDosen . '</td>
                <td ></td>
                <td></td>
                </tr>
                </tbody>
                </table>
                <p></p>
                <table border="1" style="border-collapse: collapse; width: 100%; height: 84px;">
                <tbody>
                <tr style="height: 21px;">
                <td style="width: 8.28024%; height: 21px; text-align: center;">No</td>
                <td style="width: 60.8281%; height: 21px; text-align: center;">Pertanyaan</td>
                <td style="width: 8.9172%; height: 21px; text-align: center;">Nilai</td>
                <td style="width: 21.9745%; height: 21px; text-align: center;">Keterangan</td>
                </tr>
                ' . $tableRows .
            '<tr style="height: 21px;">
                <td style="width: 8.28024%; height: 21px;"></td>
                <td style="width: 60.8281%; height: 21px;">Tingkat kepuasan mahasiswa</td>
                <td style="width: 8.9172%; height: 21px; text-align: center;">' . $rataRata . '</td>
                <td style="width: 21.9745%; height: 21px; text-align: center;">' . $keteranganRataRata . '</td>
                </tr>
                <tr style="height: 21px;">
                <td style="width: 8.28024%; height: 21px;"></td>
                <td style="width: 60.8281%; height: 21px;">jumlah Responden</td>
                <td style="width: 8.9172%; height: 21px; text-align: center;" colspan="2">' . $trkueskKdkues2['count'][0] . '</td>
                </tr>
                </tbody>
                </table>
                <p></p>
                <p style="text-align: right;">Garut ' . Date::now()->format('d') . ' ' . Carbon::now()->isoFormat('MMMM') . ' ' . Date::now()->format('Y') . '</p>
                <p style="text-align: right;">Ketua Gugus Kendali Mutu&nbsp;</p>
                <p style="text-align: right;"></p>
                <p style="text-align: right;"></p>
                <br>
                <br>
                <br>
                <br>
                <br>
                <p style="text-align: right;">Wahyu Andrias Kurniawan, M.T</p>
                <p style="text-align: right;"></p>
                <table border="1" style="border-collapse: collapse; width: 100%;">
                <tbody>
                <tr>
                <td style="width: 2.06612%;">No</td>
                <td style="width: 97.9339%;">Kesan dan Kritik dari Mahasiswa</td>
                </tr>
               ' . $tableRowsComment . '
                </tbody>
                </table>
                </body>
                </html>
                ';

        // $pdf->loadHTML($htmlContent);
        // return $pdf->stream();

        // Headers for Word export
        header("Content-Type: application/vnd.ms-word");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Disposition: attachment; filename=" . preg_replace('/[^a-zA-Z]+/', '', 'survey_' . $namaMatkul) . ".doc");


        echo $htmlContent;
    }
}
