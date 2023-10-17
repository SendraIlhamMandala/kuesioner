<?php

namespace App\Http\Controllers;

use App\Models\Kuesioner;
use App\Models\Mahasiswa;
use App\Models\Matakuliah;
use App\Models\Tahunsemester;
use App\Models\Trkuesk;
use App\Models\Trkuesl;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use mysqli;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;


    public function index() {

        if(Auth()->user()->nmmhs == 'admin'){
            return redirect()->route('profile.edit');
        }

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

    public function export() {
        


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
             $tables             = array("tblmk");
         
            //or add 5th parameter(array) of specific tables:    array("mytable1","mytable2","mytable3") for multiple tables
         
             Controller::Export_Database($mysqlHostName,$mysqlUserName,$mysqlPassword,$DbName,  $tables, $backup_name );
         
           
             return 1;
       

    }

    public function kuesionerDashboardStore(Request $request, $nimhs) {

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
        
            foreach ($updates_sl as $update) {
                Trkuesl::where('nimhs', $update['nimhs'])
                    ->where('kdkues', $update['kdkues'])
                    ->where('klkues', $update['klkues'])
                    ->where('thsms', $update['thsms'])
                    ->update($update['updateData']);
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
        
            foreach ($updates_sk as $update) {
                Trkuesk::where('nimhs', $update['nimhs'])
                    ->where('kdkues', $update['kdkues'])
                    ->where('kdkmk', $update['kdkmk'])
                    ->where('thsms', $update['thsms'])
                    ->update($update['updateData']);
            }
            return redirect()->route('dashboard');
        }
    


    public static function Export_Database($host,$user,$pass,$name,  $tables=false, $backup_name=false ){
        $mysqli = new mysqli($host,$user,$pass,$name); 
        $mysqli->select_db($name); 
        $mysqli->query("SET NAMES 'utf8'");

        $queryTables    = $mysqli->query('SHOW TABLES'); 
        while($row = $queryTables->fetch_row()) 
        { 
            $target_tables[] = $row[0]; 
        }   
        if($tables !== false) 
        { 
            $target_tables = array_intersect( $target_tables, $tables); 
        }
        foreach($target_tables as $table)
        {
            $result         =   $mysqli->query('SELECT * FROM '.$table);  
            $fields_amount  =   $result->field_count;  
            $rows_num=$mysqli->affected_rows;     
            $res            =   $mysqli->query('SHOW CREATE TABLE '.$table); 
            $TableMLine     =   $res->fetch_row();
            $content        = (!isset($content) ?  '' : $content) . "\n\n".$TableMLine[1].";\n\n";

            for ($i = 0, $st_counter = 0; $i < $fields_amount;   $i++, $st_counter=0) 
            {
                while($row = $result->fetch_row())  
                { //when started (and every after 100 command cycle):
                    if ($st_counter%100 == 0 || $st_counter == 0 )  
                    {
                            $content .= "\nINSERT INTO ".$table." VALUES";
                    }
                    $content .= "\n(";
                    for($j=0; $j<$fields_amount; $j++)  
                    { 
                        $row[$j] = str_replace("\n","\\n", addslashes($row[$j]) ); 
                        if (isset($row[$j]))
                        {
                            $content .= '"'.$row[$j].'"' ; 
                        }
                        else 
                        {   
                            $content .= '""';
                        }     
                        if ($j<($fields_amount-1))
                        {
                                $content.= ',';
                        }      
                    }
                    $content .=")";
                    //every after 100 command cycle [or at last line] ....p.s. but should be inserted 1 cycle eariler
                    if ( (($st_counter+1)%100==0 && $st_counter!=0) || $st_counter+1==$rows_num) 
                    {   
                        $content .= ";";
                    } 
                    else 
                    {
                        $content .= ",";
                    } 
                    $st_counter=$st_counter+1;
                }
            } $content .="\n\n\n";
        }
        //$backup_name = $backup_name ? $backup_name : $name."___(".date('H-i-s')."_".date('d-m-Y').")__rand".rand(1,11111111).".sql";
        $backup_name = $backup_name ? $backup_name : $name.".sql";
        header('Content-Type: application/octet-stream');   
        header("Content-Transfer-Encoding: Binary"); 
        header("Content-disposition: attachment; filename=\"".$backup_name."\"");  
        echo $content; exit;
    }

    

}
