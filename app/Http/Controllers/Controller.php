<?php

namespace App\Http\Controllers;

use App\Models\Kuesioner;
use App\Models\Mahasiswa;
use App\Models\Matakuliah;
use App\Models\Setting;
use App\Models\Tahunsemester;
use App\Models\Tbkues;
use App\Models\Tblmk;
use App\Models\Trkuesk;
use App\Models\Trkuesl;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use mysqli;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;


    public function index()
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

        if ($trkuesl->where('thsms', Tahunsemester::where('status', 'aktif')->first()->thsms)->first()->skor > 0) {
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



        $DB_HOST = "localhost";
        $DB_USER = "root";
        $DB_PASS = "";
        $DB_NAME = "kuisioner";

        //ENTER THE RELEVANT INFO BELOW
        $mysqlUserName      = "root";
        $mysqlPassword      = "";
        $mysqlHostName      = "localhost";
        $DbName             = "kuisioner";
        $backup_name        = "mybackup.sql";
        $tables             = array("trkuesl");

        //or add 5th parameter(array) of specific tables:    array("mytable1","mytable2","mytable3") for multiple tables

        Controller::Export_Database($mysqlHostName, $mysqlUserName, $mysqlPassword, $DbName,  $tables, $backup_name);


        return 1;
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

        //or add 5th parameter(array) of specific tables:    array("mytable1","mytable2","mytable3") for multiple tables
        if (Auth()->user()->nmmhs == 'admin') {
         
            Controller::Export_Database($mysqlHostName, $mysqlUserName, $mysqlPassword, $DbName,  $tables, $backup_name);
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

        //or add 5th parameter(array) of specific tables:    array("mytable1","mytable2","mytable3") for multiple tables

        if (Auth()->user()->nmmhs == 'admin') {

        Controller::Export_Database($mysqlHostName, $mysqlUserName, $mysqlPassword, $DbName,  $tables, $backup_name);
        }

        return 1;
    }



    public function kuesionerDashboardStore(Request $request, $nimhs)
    {

        $updates_sl = [];



        $thsms_active = Tahunsemester::where('status', 'aktif')->first()->thsms;

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



        $updates_sk = [];

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

        return redirect()->route('selesai');
    }



    public static function Export_Database($host, $user, $pass, $name,  $tables = false, $backup_name = false)
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
            $result         =   $mysqli->query('SELECT * FROM ' . $table);
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
        $sudah = Trkuesl::where('skor', '!=', 0)->get()->pluck('nimhs')->unique();
        $belum = Trkuesl::where('skor', 0)->get()->pluck('nimhs')->unique();

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
    
}


