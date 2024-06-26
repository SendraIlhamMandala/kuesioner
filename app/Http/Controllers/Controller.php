<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Dosen;
use App\Models\Hasil;
use App\Models\Kuesioner;
use App\Models\Mahasiswa;
use App\Models\Matakuliah;
use App\Models\Progress;
use App\Models\Setting;
use App\Models\Tahunsemester;
use App\Models\Tbkues;
use App\Models\Tblmk;
use App\Models\Trkuesk;
use App\Models\Trkuesl;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use mysqli;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;


    public function dosen()
    {

        $dosen = Dosen::select('nmdosen')->distinct()->get();
        // dd($dosen);
        return view('dosen', compact('dosen'));
    }

    public function dosenMatkul($nmdosen)
    {

        $dosen = Dosen::where('nmdosen', $nmdosen)->where('thsms', Tahunsemester::where('status', 'aktif')->first()->thsms)->get();
        // dd($dosen->pluck('kdkmk')->toArray());
        $matakuliah = [];

        foreach ($dosen as $key => $value) {

            $matakuliah[] = Tblmk::where('kdkmk', $value->kdkmk)->get();
        }

        // dd($matakuliah);

        return view('dosenMatkul', compact('dosen', 'matakuliah', 'nmdosen'));
    }

    public function index()
    {



        if (Auth()->user()->nmmhs == 'admin') {
            return redirect()->route('dashboardAdmin');
        }

        if (Setting::find(1)->is_open == 'tutup') {
            return redirect()->route('tutup');
        }

        $thsms_active = Tahunsemester::where('status', 'aktif')->first();
        // $mahasiswa = Mahasiswa::where('nimhs', Auth()->user()->nimhs)->first();
        $trkuesl = Trkuesl::where('nimhs', Auth()->user()->nimhs)->where('thsms', $thsms_active->thsms)->get();
        $kuesioner = Kuesioner::all();
        $kelas_kuesioner = collect(array_unique($trkuesl->pluck('klkues')->toArray()));
        $kode_kuesioner = collect(array_unique($trkuesl->pluck('kdkues')->toArray()));


        $trkuesk = Trkuesk::where('nimhs', Auth()->user()->nimhs)->where('thsms', $thsms_active->thsms)->get();
        // $trkuesk = Auth()->user()->trkuesks;
        // dd($trkuesk,$trkuesk1);


        $matakuliah = Matakuliah::all();
        $kuesionerA = Kuesioner::where('klkues', 'A')->get();
        $kelas_matakuliah = collect(array_unique($trkuesk->pluck('kdkmk')->toArray()));
        // dd(Auth()->user()->nimhs,$trkuesk->where('kdkmk', $kelas_matakuliah->first()),$kelas_matakuliah->first());
        // dd(Trkuesl::where('nimhs', Auth()->user()->nimhs)->get());
        
        $progress = Progress::where('user_id', Auth::user()->id)
            ->where('tahunsemester_id', Tahunsemester::where('status', 'aktif')->first()->id)
            ->first();

        $kelas_progress = $progress ? json_decode($progress->kelas, true) : null;
        $kdkmk_progress = $progress ? json_decode($progress->kdkmk, true) : null;
        $status_progress = $progress ? json_decode($progress->status, true) : null;
        // dd($progress,$progress->status,$status_progress);
        // // redirect if done 

        $incomplete = $trkuesl->where('skor', 0)->first();
            
        $incomplete_sk = $trkuesk->where('skor', 0)->first();
        
        $hasil = Hasil::where('user_id', Auth()->user()->id)->where('tahunsemester_id', $thsms_active->id)->first();
        // dd($hasil, $incomplete, $incomplete_sk);
        if ($incomplete == null && $incomplete_sk == null && !$hasil == null ) {
            return redirect()->route('dashboardComment');
        }

        $kelas = [
            'A' => 'Perkuliahan',
            'B' => 'LAYANAN ADMINISTRASI AKADEMIK',
            'C' => 'LAYANAN KEMAHASISWAAN',
            'D' => 'LAYANAN PERPUSTAKAAN',
            'E' => 'LAYANAN SARANA PRASARANA',
            'F' => 'LAYANAN KEUANGAN'
        ];

        return view('dashboard', compact(
            // 'mahasiswa',
            'trkuesl',
            'kuesioner',
            'kelas_kuesioner',
            'kode_kuesioner',
            'kelas',
            'trkuesk',
            'matakuliah',
            'kelas_matakuliah',
            'kuesionerA',
            'kelas_progress',
            'kdkmk_progress',
            'status_progress',


        ));
    }

    public function show($nimhs)
    {
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
            'kelas'
        ));
    }


    public function showsk($nimhs)
    {
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

    public function export()
    {
        $tblmk = Tblmk::all()->pluck('kdkmk')->toArray();
        $kelas = [
            'A',
            'B',
            'C',
            'D',
            'E',
            'F'
        ];

        $kelasNama = [
            'A' => 'Perkuliahan',
            'B' => 'LAYANAN ADMINISTRASI AKADEMIK',
            'C' => 'LAYANAN KEMAHASISWAAN',
            'D' => 'LAYANAN PERPUSTAKAAN',
            'E' => 'LAYANAN SARANA PRASARANA',
            'F' => 'LAYANAN KEUANGAN'
        ];
        $thsms_active = Tahunsemester::where('status', 'aktif')->first()->thsms;

        $trkuesk2 = Trkuesk::whereIn('kdkmk', $tblmk)->where('skor', '!=', 0)->where('thsms', $thsms_active)->pluck('kdkmk')->unique();
        $trkuesl = Trkuesl::whereIn('klkues', $kelas)->where('skor', '!=', 0)->where('thsms', $thsms_active)->pluck('klkues')->unique();
        $matkul = Tblmk::whereIn('kdkmk', $trkuesk2)->get();
        $layanan = collect($kelasNama)->filter(function ($value, $key) use ($trkuesl) {
            return $trkuesl->contains($key);
        });
        return view('export', compact('trkuesk2', 'matkul', 'layanan'));


        // $DB_HOST = "localhost";
        // $DB_USER = "root";
        // $DB_PASS = "";
        // $DB_NAME = "kuisioner";

        // //ENTER THE RELEVANT INFO BELOW
        // $mysqlUserName      = "root";
        // $mysqlPassword      = "";
        // $mysqlHostName      = "localhost";
        // $DbName             = "kuisioner";
        // $backup_name        = "mybackup.sql";
        // $tables             = array("trkuesl");

        // //or add 5th parameter(array) of specific tables:    array("mytable1","mytable2","mytable3") for multiple tables

        // Controller::Export_Database($mysqlHostName, $mysqlUserName, $mysqlPassword, $DbName,  $tables, $backup_name);


        // return 1;
    }

    public function exportSk()
    {



        $DB_HOST = "localhost";
        $DB_USER = "root";
        $DB_PASS = "";
        $DB_NAME = "kuisioner";

        //ENTER THE RELEVANT INFO BELOW
        $mysqlUserName      = "root";
        $mysqlPassword      = "";
        $mysqlHostName      = "localhost";
        $DbName             = "kuisioner";
        $backup_name        = "TRKUESK.sql";
        $tables             = array("trkuesk");
        $thsms_active       = Tahunsemester::where('status', 'aktif')->first()->thsms;

        //or add 5th parameter(array) of specific tables:    array("mytable1","mytable2","mytable3") for multiple tables
        if (Auth()->user()->nmmhs == 'admin') {

            Controller::Export_Database($mysqlHostName, $mysqlUserName, $mysqlPassword, $DbName,  $tables, $backup_name, $thsms_active);
        }


        return 1;
    }


    public function exportSl()
    {



        $DB_HOST = "localhost";
        $DB_USER = "root";
        $DB_PASS = "";
        $DB_NAME = "kuisioner";

        //ENTER THE RELEVANT INFO BELOW
        $mysqlUserName      = "root";
        $mysqlPassword      = "";
        $mysqlHostName      = "localhost";
        $DbName             = "kuisioner";
        $backup_name        = "TRKUESL.sql";
        $tables             = array("trkuesl");
        $thsms_active       = Tahunsemester::where('status', 'aktif')->first()->thsms;


        //or add 5th parameter(array) of specific tables:    array("mytable1","mytable2","mytable3") for multiple tables

        if (Auth()->user()->nmmhs == 'admin') {

            Controller::Export_Database($mysqlHostName, $mysqlUserName, $mysqlPassword, $DbName,  $tables, $backup_name , $thsms_active);
        }

        return 1;
    }



    public function kuesionerDashboardStore(Request $request, $nimhs)
    {

        // dd($request->all(), json_encode($request->survey), $request->survey, Tahunsemester::where('status', 'aktif')->first()->id);
        $updates_sl = [];



        $thsms_active = Tahunsemester::where('status', 'aktif')->first()->thsms;

        if (isset($request['skor_sl']) && is_array($request['skor_sl']) && count($request['skor_sl']) > 0) {
            foreach ($request['skor_sl'] as $kode => $kelas) {
                foreach ($kelas as $kelas_key => $skor) {
                    $updateData = [
                        'skor' => $skor
                    ];

                    $updates_sl[] = [
                        'nimhs' => $nimhs,
                        'kdkues' => $kode,
                        'klkues' => $kelas_key,
                        'thsms' => $thsms_active,
                        'updateData' => $updateData
                    ];
                }
            }


            $batch_size = 100;

            for ($i = 0; $i < count($updates_sl); $i += $batch_size) {
                $batch = array_slice($updates_sl, $i, $batch_size);

                foreach ($batch as $update) {
                    Trkuesl::where('nimhs', $update['nimhs'])
                        ->where('kdkues', $update['kdkues'])
                        ->where('klkues', $update['klkues'])
                        ->where('thsms', $update['thsms'])
                        ->update($update['updateData']);
                }
            }
        }


        $updates_sk = [];

        if (isset($request['skor_sk']) && is_array($request['skor_sk']) && count($request['skor_sk']) > 0) {
            foreach ($request['skor_sk'] as $kodekues => $kodematkul) {
                foreach ($kodematkul as $kodematkul_key => $skor) {
                    $updateData = [
                        'skor' => $skor
                    ];

                    $updates_sk[] = [
                        'nimhs' => $nimhs,
                        'kdkues' => $kodekues,
                        'kdkmk' => $kodematkul_key,
                        'thsms' => $thsms_active,
                        'updateData' => $updateData
                    ];
                }
            }
            $batch_size = 50;

            for ($i = 0; $i < count($updates_sk); $i += $batch_size) {
                $batch = array_slice($updates_sk, $i, $batch_size);

                foreach ($batch as $update) {
                    Trkuesk::where('nimhs', $update['nimhs'])
                        ->where('kdkues', $update['kdkues'])
                        ->where('kdkmk', $update['kdkmk'])
                        ->where('thsms', $update['thsms'])
                        ->update($update['updateData']);
                }
            }
        }

        $hasil = Hasil::create([
            'hasil' => json_encode($request->survey),
            'user_id' => Auth()->user()->id,
            'tahunsemester_id' =>  Tahunsemester::where('status', 'aktif')->first()->id
        ]);

        $hasil->save();

        return redirect()->route('dashboardComment');
    }

    public function dashboardComment()
    {
        $thsms_active = Tahunsemester::where('status',  'aktif')->first();

        if (Auth()->user()->nmmhs == 'admin') {
            return redirect()->route('profile.edit');
        }

        if (Setting::find(1)->is_open == 'tutup') {
            return redirect()->route('tutup');
        }

        // dd(Auth()->user()->comments->first());
        $existingComment = Auth()->user()->comments->where('tahunsemester_id', $thsms_active->id)->first();
        if ($existingComment) {
            return redirect()->route('selesai');
        }

        $mahasiswa = Mahasiswa::where('nimhs', Auth()->user()->nimhs)->first();
        $trkuesl = Trkuesl::where('nimhs', Auth()->user()->nimhs)->where('thsms', $thsms_active->thsms)->get();
        $kuesioner = Kuesioner::all();
        $kelas_kuesioner = collect(array_unique($trkuesl->pluck('klkues')->toArray()));
        $kode_kuesioner = collect(array_unique($trkuesl->pluck('kdkues')->toArray()));


        $trkuesk = Trkuesk::where('nimhs', Auth()->user()->nimhs)->where('thsms', $thsms_active->thsms)->get();



        $matakuliah = Matakuliah::all();
        $kuesionerA = Kuesioner::where('klkues', 'A')->get();
        $kelas_matakuliah = collect(array_unique($trkuesk->pluck('kdkmk')->toArray()));
        // dd(Auth()->user()->nimhs,$trkuesk->where('kdkmk', $kelas_matakuliah->first()),$kelas_matakuliah->first());

        // if ($trkuesl->where('thsms', Tahunsemester::where('status', 'aktif')->first()->thsms)->first()->skor > 0) {
        //     return redirect()->route('selesai');
        // }

        $kelas = [
            'A' => 'Perkuliahan',
            'B' => 'LAYANAN ADMINISTRASI AKADEMIK',
            'C' => 'LAYANAN KEMAHASISWAAN',
            'D' => 'LAYANAN PERPUSTAKAAN',
            'E' => 'LAYANAN SARANA PRASARANA',
            'F' => 'LAYANAN KEUANGAN'
        ];

        return view('dashboardComment', compact(
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



    public static function Export_Database($host, $user, $pass, $name,  $tables = false, $backup_name = false , $thsms)
    {
        $mysqli = new mysqli($host, $user, $pass, $name);
        $mysqli->select_db($name);
        $mysqli->query("SET NAMES 'utf8'");

        $queryTables    = $mysqli->query('SHOW TABLES');
        while ($row = $queryTables->fetch_row()) {
            $target_tables[] = $row[0];
        }
        
        if ($tables !== false) {
            $target_tables = array_intersect($target_tables, $tables);
        }

        foreach ($target_tables as $table) {
            $result         =   $mysqli->query('SELECT * FROM ' . $table . ' where thsms = ' . $thsms );
            $fields_amount  =   $result->field_count;
            $rows_num = $mysqli->affected_rows;
            $res            =   $mysqli->query('SHOW CREATE TABLE ' . $table);
            $TableMLine     =   $res->fetch_row();
            $content        = (!isset($content) ?  '' : $content) . "\n\n" . $TableMLine[1] . ";\n\n";

            for ($i = 0, $st_counter = 0; $i < $fields_amount; $i++, $st_counter = 0) {
                while ($row = $result->fetch_row()) { //when started (and every after 100 command cycle):
                    if ($st_counter % 100 == 0 || $st_counter == 0) {
                        $content .= "\nINSERT INTO " . $table . " VALUES";
                    }
                    $content .= "\n(";
                    for ($j = 0; $j < $fields_amount; $j++) {
                        $row[$j] = str_replace("\n", "\\n", addslashes($row[$j]));
                        if (isset($row[$j])) {
                            $content .= '"' . $row[$j] . '"';
                        } else {
                            $content .= '""';
                        }
                        if ($j < ($fields_amount - 1)) {
                            $content .= ',';
                        }
                    }
                    $content .= ")";
                    //every after 100 command cycle [or at last line] ....p.s. but should be inserted 1 cycle eariler
                    if ((($st_counter + 1) % 100 == 0 && $st_counter != 0) || $st_counter + 1 == $rows_num) {
                        $content .= ";";
                    } else {
                        $content .= ",";
                    }
                    $st_counter = $st_counter + 1;
                }
            }
            $content .= "\n\n\n";
        }
        //$backup_name = $backup_name ? $backup_name : $name."___(".date('H-i-s')."_".date('d-m-Y').")__rand".rand(1,11111111).".sql";
        $backup_name = $backup_name ? $backup_name : $name . ".sql";
        header('Content-Type: application/octet-stream');
        header("Content-Transfer-Encoding: Binary");
        header("Content-disposition: attachment; filename=\"" . $backup_name . "\"");
        echo $content;
        exit;
    }

    public function selesai()
    {

        return view('selesai');
    }

    public function tutup()
    {

        return view('tutup');
    }


    public function editKuesioner()
    {

        if (Auth()->user()->nmmhs == 'admin') {
            return redirect()->route('profile.edit');
        }


        if (Setting::find(1)->is_open == 'tutup') {
            return redirect()->route('tutup');
        }


        $thsms_active = Tahunsemester::where('status', 'aktif')->first();
        $mahasiswa = Mahasiswa::where('nimhs', Auth()->user()->nimhs)->first();
        $trkuesl = Trkuesl::where('nimhs', Auth()->user()->nimhs)->where('thsms', $thsms_active->thsms)->get();
        $kuesioner = Kuesioner::all();
        $kelas_kuesioner = collect(array_unique($trkuesl->pluck('klkues')->toArray()));
        $kode_kuesioner = collect(array_unique($trkuesl->pluck('kdkues')->toArray()));


        $trkuesk = Trkuesk::where('nimhs', Auth()->user()->nimhs)->where('thsms', $thsms_active->thsms)->get();



        $matakuliah = Matakuliah::all();
        $kuesionerA = Kuesioner::where('klkues', 'A')->get();
        $kelas_matakuliah = collect(array_unique($trkuesk->pluck('kdkmk')->toArray()));
        // dd(Auth()->user()->nimhs,$trkuesk->where('kdkmk', $kelas_matakuliah->first()),$kelas_matakuliah->first());



        if ($trkuesl->where('thsms', Tahunsemester::where('status', 'aktif')->first()->thsms)->first()->skor == 0) {
            return redirect()->route('selesai');
        }

        $kelas = [
            'A' => 'Perkuliahan',
            'B' => 'LAYANAN ADMINISTRASI AKADEMIK',
            'C' => 'LAYANAN KEMAHASISWAAN',
            'D' => 'LAYANAN PERPUSTAKAAN',
            'E' => 'LAYANAN SARANA PRASARANA',
            'F' => 'LAYANAN KEUANGAN'
        ];

        return view('editKuesioner', compact(
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

    public function dashboardAdmin()
    {
        //where skor not 0 

        $thsms = Tahunsemester::where('status', 'aktif')->first();
        $thsms_id = $thsms->id;
        $thsms_active = $thsms->thsms;

        $sudah = Trkuesl::where('skor', '!=', 0)->where('thsms', $thsms_active)->get()->pluck('nimhs')->unique();
        $belum = Trkuesl::where('skor', 0)->where('thsms', $thsms_active)->get()->pluck('nimhs')->unique();

        foreach ($sudah as $key => $value) {
            $user_sudah = User::where('nimhs', $value)->first();
            $nama_sudah[$key] = ['nama' => $user_sudah->nmmhs, 'nim' => $user_sudah->nimhs];
        }

        foreach ($belum as $key => $value) {
            $nama_belum_temp[$key] = User::where('nimhs', $value)->first();

            if (!is_null($nama_belum_temp[$key])) {
                $nama_belum[$key] = ['nama' => $nama_belum_temp[$key]->nmmhs, 'nim' => $nama_belum_temp[$key]->nimhs];
            }
        }
        if ($sudah->isEmpty()) {
            $nama_sudah = [['nama' => 'belum ada', 'nim' => 'belum ada']];
        }



        return view('dashboardAdmin', compact(
            'nama_sudah',
            'nama_belum',
        ));
    }

    //export average
    public function exportAverage()
    {
        $average_scores = Trkuesl::average('skor'); // Assuming 'skor' is the score field

        // Export to a csv or any other format as per your requirements
        // Here's an example with csv
        $filename = 'average_scores.csv';
        $handle = fopen($filename, 'w');
        fputcsv($handle, ['Average Score']);
        fputcsv($handle, [$average_scores]);
        fclose($handle);

        return response()->download($filename);
    }

    public function showScore()
    {

        $tblmk = Tblmk::all()->pluck('kdkmk')->unique();
        $thisUser = User::where('nimhs', 24012120038)->first();

        $thisUserScores = Trkuesk::where('nimhs', $thisUser->nimhs)->whereIn('kdkmk', $tblmk)->get();
        foreach ($thisUserScores as $key => $value) {
            //average score
            $scoresThisUser[$value->kdkmk][$key] = $value->skor;
        }

        //scores all kdmk
        foreach ($tblmk as $key => $value) {
            $count = count(Trkuesk::where('kdkmk', $value)->where('skor', '!=', 0)->pluck('skor')->toArray());
            $sum = array_sum(Trkuesk::where('kdkmk', $value)->pluck('skor')->toArray());
            //avoid divide by zero 
            $average = $sum / ($count > 0 ? $count : 1);

            $scoresAll['average'][$value] = $average;
            $scoresAll['count'][$value] = $count;
            $scoresAll['sum'][$value] = $sum;
        }

        // $thisKdmk = Trkuesk::where('kdkmk', 'FAK1011')->get();
        // dd($thisKdmk);

        //average each kdmk in scores
        foreach ($scoresThisUser as $key => $value) {
            $scoresThisUser2[$key] = array_sum($value) / count($value);
        }
        dd($tblmk, $thisUser, $scoresThisUser, $scoresThisUser2, $scoresAll);

        return view('showScore', compact('scores'));
    }

    public function exportMatkul($matkul)
    {

        $thsms = Tahunsemester::where('status', 'aktif')->first();
        $thsms_id = $thsms->id;
        $thsms_active = $thsms->thsms;
        
        $dosen = Dosen::where('kdkmk', $matkul)->where('thsms', $thsms_active)->pluck('nmdosen')->toArray();
        $namaDosen = '';
        foreach ($dosen as $key => $value) {
            $namaDosen .= '<div>' . $key + 1 . '. ' .  $value . ' </div>';
        }
        // dd($dosen, $namaDosen , $matkul);
        $namaMatkul = Tblmk::where('kdkmk', $matkul)->first()->nakmk;

        $tblmk = Tblmk::all()->pluck('kdkmk')->toArray();

        $trkuesk2 = Trkuesk::whereIn('kdkmk', $tblmk)->where('skor', '!=', 0)->pluck('kdkmk')->unique();

     

        $trkuesk = Trkuesk::where('kdkmk', $matkul)->where('skor', '!=', 0)->where('thsms', $thsms_active) ->get();
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




        // dd($trkuesk,$trkueskKdkues2,$trkueskKdkues);
        $pdf = App::make('dompdf.wrapper');

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

        $comments = Comment::where('kdkmk', $matkul)->where('tahunsemester_id', $thsms_id)->get();


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
            <td > '.Setting::find(2)->is_open .'</td>
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

    public function exportLayanan($layanan)
    {


        $kelas = [
            'A' => 'Perkuliahan',
            'B' => 'LAYANAN ADMINISTRASI AKADEMIK',
            'C' => 'LAYANAN KEMAHASISWAAN',
            'D' => 'LAYANAN PERPUSTAKAAN',
            'E' => 'LAYANAN SARANA PRASARANA',
            'F' => 'LAYANAN KEUANGAN'
        ];
        $namalayanan = $kelas[$layanan];


        $tblmk = Tblmk::all()->pluck('kdkmk')->toArray();

        $trkuesk2 = Trkuesk::whereIn('kdkmk', $tblmk)->where('skor', '!=', 0)->pluck('kdkmk')->unique();
        $thsms = Tahunsemester::where('status', 'aktif')->first();
        $thsms_id = $thsms->id;
        $thsms_active = $thsms->thsms;

        $trkuesl = Trkuesl::where('klkues', $layanan)->where('skor', '!=', 0)->where('thsms', $thsms_active)->get();
        
        $trkueslklkues = $trkuesl->pluck('kdkues')->unique();
        $trkueslklkues2['total'] = 0;
        // dd($trkuesl->pluck('skor')->toArray(),$trkueslklkues);

        foreach ($trkueslklkues as $key => $value) {

            $skor = $trkuesl->where('kdkues', $value)->pluck('skor')->toArray();
            $trkueslklkues2['average'][$key] = ['keter' => Tbkues::where('klkues', $layanan)->where('kdkues', $value)->first()->keter, 'skor' => array_sum($skor) / count($skor)];
            $trkueslklkues2['sum'][$key] = array_sum($skor);
            $trkueslklkues2['count'][$key] = count($skor);

            $trkueslklkues2['total'] = $trkueslklkues2['total'] + $trkueslklkues2['average'][$key]['skor'];
        }




        // dd($trkueslklkues2);
        $pdf = App::make('dompdf.wrapper');

        $tableRows = '';

        foreach ($trkueslklkues2['average'] as $key => $value) {
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

        $rataRata = round($trkueslklkues2['total'] / count($trkueslklkues2['average']), 2);
        $keteranganRataRata = $rataRata < 4 && $rataRata >= 3 ? 'Baik' : ($rataRata < 3 && $rataRata >= 2 ? 'Kurang' : ($rataRata < 2 && $rataRata >= 1 ? 'Sangat Kurang' : ($rataRata < 1 && $rataRata >= 0 ? 'Sangat Kurang' : 'Sangat Baik')));



        $tableRowsComment = '';

        $comments = Comment::where('klkues', $layanan)->where('tahunsemester_id', $thsms_id)->get();
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
            <td style="width: 19.1613%;">Program studi</td>
            <td style="width: 4.56481%;">:</td>
            <td style="width: 33.8642%;">Ilmu Administrasi Negara</td>
            <td style="width: 20.2361%;">Tahun Akademik</td>
            <td style="width: 2.2425%;">:</td>
            <td style="width: 19.9309%;"> '.Setting::find(2)->is_open .'</td>
            </tr>
            <tr>
            <td style="width: 19.1613%;">Layanan</td>
            <td style="width: 4.56481%;">:</td>
            <td style="width: 33.8642%;">' . ucwords(strtolower($namalayanan)) . ' </td>
            <td style="width: 20.2361%;"></td>
            <td style="width: 2.2425%;"></td>
            <td style="width: 19.9309%;"></td>
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
            <td style="width: 8.9172%; height: 21px; text-align: center;" colspan="2">' . $trkueslklkues2['count'][0] . '</td>
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
        header("content-disposition: attachment;filename=" . 'survey_' . $namalayanan . ".doc");


        echo $htmlContent;
    }

    public function exportHasilSurvey($thsms)
    {

        $hasils = Hasil::where('tahunsemester_id', $thsms)->get();
        if (!$hasils->isNotEmpty()) {
            dd('tidak ada data');
        }
        $hasilArray = [];
        foreach ($hasils as $key => $value) {
            $hasilArray[$key] = json_decode($value->hasil, true);
            $hasilArray[$key]['name'] = $value->user->nmmhs;
            $hasilArray[$key]['nim'] = $value->user->nimhs;
            // dump($value->hasil);
        }
        // dd($hasilArray, $hasils);





        $rangeValue = [
            'sangat_baik' => 4,
            'cukup' => 3,
            'kurang' => 2,
        ];

        $sum = [];
        $average = [];
        $total = 0;
        $totalAverage = 0;

        //   dd(collect($hasilArray)->pluck('range'));

        foreach ($hasilArray as $key => $hasilArrayValue) {
            foreach ($hasilArrayValue['range'] as $key2 => $value) {


                if (isset($sum[$key2])) {
                    $sum[$key2] += $rangeValue[$value];
                } else {
                    $sum[$key2] = $rangeValue[$value];
                }
                $average[$key2] = $sum[$key2] / count($hasilArray);

                $total += $sum[$key2] / count($hasilArray);
            }

            // $skor = $trkuesl->where('kdkues', $value)->pluck('skor')->toArray();
            // $trkueslklkues2['average'][$key] = ['keter' => Tbkues::where('klkues', $layanan)->where('kdkues', $value)->first()->keter, 'skor' => array_sum($skor) / count($skor)];
            // $trkueslklkues2['sum'][$key] = array_sum($skor);
            // $trkueslklkues2['count'][$key] = count($skor);

            // $trkueslklkues2['total'] = $trkueslklkues2['total'] + $trkueslklkues2['average'][$key]['skor'];
        }
        $totalAverage = array_sum($average) / count($average);
        // dd($sum, $average, $total, $totalAverage, array_sum($average), count($average));


        $keteranganTotalAverage = $totalAverage < 4 && $totalAverage >= 3 ? 'Baik' : ($totalAverage < 3 && $totalAverage >= 2 ? 'Kurang' : ($totalAverage < 2 && $totalAverage >= 1 ? 'Sangat Kurang' : ($totalAverage < 1 && $totalAverage >= 0 ? 'Sangat Kurang' : 'Sangat Baik')));


        $tableRows = '';

        $number = 0;
        foreach ($hasilArrayValue['range'] as $key => $value) {
            $number++;
            $keterangan = $average[$key] < 4 && $average[$key] >= 3 ? 'Baik' : ($average[$key] < 3 && $average[$key] >= 2 ? 'Kurang' : ($average[$key] < 2 && $average[$key] >= 1 ? 'Sangat Kurang' : ($average[$key] < 1 && $average[$key] >= 0 ? 'Sangat Kurang' : 'Sangat Baik')));
            $tableRows .= '
        <tr style="height: 21px;">
        <td style="width: 8.28024%; height: 21px; text-align: center;"> ' . $number . '</td>
        <td style="width: 60.8281%; height: 21px; ">' . $key . '</td>
        <td style="width: 8.9172%; height: 21px; text-align: center; ">' . round($average[$key], 2) . '</td>
        <td style="width: 21.9745%; height: 21px; text-align: center;">' . $keterangan . '</td>
        </tr>';
        }

        // dd($tableRows);



        $tableRowsComment = '';

        $comments = [];

        foreach ($hasilArray as $key => $hasilArrayValue) {
            foreach ($hasilArrayValue['string'] as $key2 => $value) {
                $comments[$key2][] = $value;
            }
        }

        $judulComment = '';
        foreach ($comments as $key => $value) {
            $nomor_comment = 0;

            $judulComment .= ' 

            <br>
            <table border="1" style="border-collapse: collapse; width: 100%;">
            <tbody>
            <tr>
            <td style="width: 2.06612%;">No</td>
            <td style="width: 97.9339%;">' . $key . '</td>
            </tr>';
            foreach ($value as $key2 => $value2) {
                # code...
                $nomor_comment += 1;
                $judulComment .= '
                <tr style="height: 21px;">
                <td style="width: 8.28024%; height: 21px; text-align: center;"> ' . $nomor_comment . '</td>
                <td style="width: 60.8281%; height: 21px; ">' . $value2 . '</td>
                </tr>';
            }
            $judulComment .= '  </tbody></table>';
        }

        // dd($comments,$judulComment);
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
            <td style="width: 89.0047%; height: 21px; text-align: center;" colspan="6"><strong>TINGKAT KEPUASAN MAHASISWA TERHADAP PENERIMAAN  MAHASISWA BARU</strong></td>

            </tr>
            </tbody>
            </table>
            <p></p>
            <table border="0" style="width: 100%; border-collapse: collapse;">
            <tbody>
            <tr>
            <td style="width: 19.1613%;">Program studi</td>
            <td style="width: 4.56481%;">:</td>
            <td style="width: 33.8642%;">Ilmu Administrasi Negara</td>
            <td style="width: 20.2361%;">Tahun Akademik</td>
            <td style="width: 2.2425%;">:</td>
            <td style="width: 19.9309%;"> '.Setting::find(2)->is_open .'</td>
            </tr>
            <tr>
            <td style="width: 19.1613%;">Layanan</td>
            <td style="width: 4.56481%;">:</td>
            <td style="width: 33.8642%;"> Penerimaan Mahasiswa Baru </td>
            <td style="width: 20.2361%;"></td>
            <td style="width: 2.2425%;"></td>
            <td style="width: 19.9309%;"></td>
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
            <td style="width: 8.9172%; height: 21px; text-align: center;">' . round($totalAverage, 2)  . '</td>
            <td style="width: 21.9745%; height: 21px; text-align: center;">' . $keteranganTotalAverage . '</td>
            </tr>
            <tr style="height: 21px;">
            <td style="width: 8.28024%; height: 21px;"></td>
            <td style="width: 60.8281%; height: 21px;">jumlah Responden</td>
            <td style="width: 8.9172%; height: 21px; text-align: center;" colspan="2">' . count($hasilArray) . '</td>
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
            
           ' . $judulComment . '
          
            </body>
            </html>
            ';

        // $pdf->loadHTML($htmlContent);
        // return $pdf->stream();

        // Headers for Word export
        header("Content-Type: application/vnd.ms-word");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("content-disposition: attachment;filename=" . 'survey_Penerimaan_Mahasiswa_Baru_' . Tahunsemester::where('id', $thsms)->first()->thsms . ".doc");


        echo $htmlContent;
    }

    function dashboardCommentStore(Request $request, $nimhs)
    {
        $user = User::where('nimhs', $nimhs)->first();
        $tahunsemesterId = Tahunsemester::where('status', 'aktif')->first()->id;
        if ($request->has('kdkmk')) {
            foreach ($request['kdkmk'] as $kdkmk => $comment) {
                $comment = $comment ?? '-'; // Replace null with '-'
                Comment::create([
                    'comment' => $comment,
                    'kdkmk' => $kdkmk,
                    'user_id' => $user->id,
                    'tahunsemester_id' => $tahunsemesterId
                ]);
            }
        }

        if ($request->has('klkues')) {
            foreach ($request['klkues'] as $klkues => $comment) {
                $comment = $comment ?? '-'; // Replace null with '-'
                Comment::create([
                    'comment' => $comment,
                    'klkues' => $klkues,
                    'user_id' => $user->id,
                    'tahunsemester_id' => $tahunsemesterId
                ]);
            }
        }
        return redirect('/dashboard');
    }

    public function saveKuesSl(Request $request)
    {
        // return response()->json($request->all());
                   
        $kues = json_decode($request['kues'], true);
        $kelas = json_decode($request['kelas'], true);

        $updates_sl = [];

        $nimhs = Auth::user()->nimhs;

        $thsms_active = Tahunsemester::where('status', 'aktif')->first()->thsms;
       
        $current_progress =  Progress::where('user_id', Auth::user()->id)->where('tahunsemester_id', Tahunsemester::where('status', 'aktif')->first()->id)->first();
        
        // return response()->json($current_progress);


        try {
            if (isset($kues) && is_array($kues) && count($kues) > 0) {
                foreach ($kues as $kode => $skor) {
                    $updateData = [
                        'skor' => $skor
                    ];

                    $updates_sl[] = [
                        'nimhs' => $nimhs,
                        'kdkues' => $kode,
                        'klkues' => $kelas,
                        'thsms' => $thsms_active,
                        'updateData' => $updateData
                    ];
                }

                $batch_size = 100;

                for ($i = 0; $i < count($updates_sl); $i += $batch_size) {
                    $batch = array_slice($updates_sl, $i, $batch_size);

                    foreach ($batch as $update) {
                        Trkuesl::where('nimhs', $update['nimhs'])
                            ->where('kdkues', $update['kdkues'])
                            ->where('klkues', $update['klkues'])
                            ->where('thsms', $update['thsms'])
                            ->update($update['updateData']);
                    }
                }
            }

            Progress::updateOrCreate(
                [
                    'user_id' => Auth::user()->id,
                    'tahunsemester_id' => Tahunsemester::where('status', 'aktif')->first()->id,
                ],
                [
                    'kelas' => json_encode(array_merge(json_decode(optional($current_progress)->kelas, true) ?: [], [$kelas])),
                ]
            );

               return response()->json(['message' => 'Data berhasil disimpan.', 'color' => 'green']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage() , 'color' => 'red']);
        }



        return response()->json(['kues' => $kues, 'kelas' => $kelas]);
    }

    public function saveKuesSk(Request $request)
    {

        
        $kues_sk = json_decode($request['kues'], true);
        // return response()->json($kues_sk);
        $kodematkul = json_decode($request['kelas'], true);

        $updates_sk = [];
        $updateData = [];
        $nimhs = Auth::user()->nimhs;

        $thsms_active = Tahunsemester::where('status', 'aktif')->first()->thsms;
       
        $current_progress =  Progress::where('user_id', Auth::user()->id)->where('tahunsemester_id', Tahunsemester::where('status', 'aktif')->first()->id)->first();
        
        // return response()->json($current_progress);


        try {
            if (isset($kues_sk) && is_array($kues_sk) && count($kues_sk) > 0) {
                foreach ($kues_sk as $kode => $skor) {
                    $updateData = [
                        'skor' => $skor
                    ];

                       $updates_sk[] = [
                        'nimhs' => $nimhs,
                        'kdkues' => $kode,
                        'kdkmk' => $kodematkul,
                        'thsms' => $thsms_active,
                        'updateData' => $updateData
                    ];
                }

                $batch_size = 100;

                for ($i = 0; $i < count($updates_sk); $i += $batch_size) {
                    $batch = array_slice($updates_sk, $i, $batch_size);

                    foreach ($batch as $update) {
                       Trkuesk::where('nimhs', $update['nimhs'])
                        ->where('kdkues', $update['kdkues'])
                        ->where('kdkmk', $update['kdkmk'])
                        ->where('thsms', $update['thsms'])
                        ->update($update['updateData']);
                    }
                }
            }

            Progress::updateOrCreate(
                [
                    'user_id' => Auth::user()->id,
                    'tahunsemester_id' => Tahunsemester::where('status', 'aktif')->first()->id,

                ],
                [
                    'kdkmk' => json_encode(array_merge(json_decode(optional($current_progress)->kdkmk, true) ?: [], [$kodematkul])),
                ]
            );

            // return response()->json($updateData);
            return response()->json(['message' => 'Data berhasil disimpan.', 'color' => 'green']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage() , 'color' => 'red']);
        }



        return response()->json(['kues' => $kues_sk, 'kodematkul' => $kodematkul]);
    }

    public function saveKuesHasil(Request $request)
    // public function saveKuesHasil()
    {
        $request->validate([
            'kues' => 'required|json',
        ]);

      
        $kues = json_decode($request->kues, true);
        $array = [];
        foreach ($kues as $key => $value) {
     
            [$mainCategory, $subCategory] = explode('_', $key, 2) + ['', null];
            $array[$mainCategory][$subCategory] = $value;
            if ($subCategory === null) {
                $array[$mainCategory] = $value;
            }
        }
        // return response()->json($array);

        $tahunsemesterId = Tahunsemester::where('status', 'aktif')->first()->id ;
        
        try {
            $hasil = Hasil::create([
                'hasil' => json_encode($array),
                'user_id' => Auth()->user()->id,
                'tahunsemester_id' =>  $tahunsemesterId
            ]);
    
            $hasil->save();


            Progress::updateOrCreate(
                [
                    'user_id' => Auth::user()->id,
                    'tahunsemester_id' => $tahunsemesterId,
                ],
                [
                    'status' => '["hasil"]',

                ]
            );
    
                       
            return response()->json(['success' => 'Data berhasil disimpan.']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
     
    }

    public static function image_logo_base64()
    {
        return 'data:image/png;base64,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';
    }
}
