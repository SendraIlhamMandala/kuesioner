<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TahunsemesterController;
use App\Models\Comment;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

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
use Illuminate\Mail\Mailables\Content;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Date;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('dashboard');
    return view('welcome');
});

Route::get('/dashboard', function () {
    return redirect()->route('dashboard');
    return view('welcome');
});



Route::get('/kuesioner', [Controller::class, 'index'])->name('dashboard')->middleware('auth');

Route::get('/index', function () {
    return view('index');
})->name('index');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::get('/mahasiswa', function () {
    $mahasiswas = Mahasiswa::all();

    return view('mahasiswa', compact('mahasiswas'));
});


Route::get('/mahasiswa/{nimhs}', [Controller::class, 'show'])->name('mahasiswa.show');
Route::get('/mahasiswa/sk/{nimhs}', [Controller::class, 'showsk'])->name('mahasiswa.showsk');

Route::post('/mahasiswa/{nimhs}', function (Request $request, $nimhs) {

    $updates = [];

    foreach ($request['skor'] as $kode => $kelas) {
        foreach ($kelas as $kelas_key => $skor) {
            $updateData = [
                'skor' => $skor
            ];

            $updates[] = [
                'nimhs' => $nimhs,
                'kdkues' => $kode,
                'klkues' => $kelas_key,
                'updateData' => $updateData
            ];
        }
    }

    foreach ($updates as $update) {
        Trkuesl::where('nimhs', $update['nimhs'])
            ->where('kdkues', $update['kdkues'])
            ->where('klkues', $update['klkues'])
            ->update($update['updateData']);
    }
    return redirect('/mahasiswa/' . $nimhs);
})->name('kuesioner.store');


Route::post('/mahasiswa/sk/{nimhs}', function (Request $request, $nimhs) {
    $updates = [];

    foreach ($request['skor'] as $kodekues => $kodematkul) {
        foreach ($kodematkul as $kodematkul_key => $skor) {
            $updateData = [
                'skor' => $skor
            ];

            $updates[] = [
                'nimhs' => $nimhs,
                'kdkues' => $kodekues,
                'kdkmk' => $kodematkul_key,
                'updateData' => $updateData
            ];
        }
    }

    foreach ($updates as $update) {
        Trkuesk::where('nimhs', $update['nimhs'])
            ->where('kdkues', $update['kdkues'])
            ->where('kdkmk', $update['kdkmk'])
            ->update($update['updateData']);
    }
    return redirect('/mahasiswa/sk/' . $nimhs);
})->name('kuesionerSk.store');

Route::post('/dashboard/{nimhs}', [Controller::class,'kuesionerDashboardStore'] )->name('kuesionerDashboard.store');


Route::get('/createuser', function () {
    $mahasiswas = Mahasiswa::all();
    $users = [];
    foreach ($mahasiswas as $key => $value) {
        $users[] = [
            'nimhs' => $value->nimhs,
            'nmmhs' => $value->nmmhs,
            'email' => $value->email,
            'password' => bcrypt($value->nimhs),
            'created_at' => now(),
        ];
    }

    User::insert($users);
});

Route::get('/export', [Controller::class,'export'] )->middleware('auth')->name('export');
Route::get('/export-sk', [Controller::class,'exportSk'] )->middleware('auth')->name('export.sk');
Route::get('/export-sl', [Controller::class,'exportSl'] )->middleware('auth')->name('export.sl');


Route::resource('/tahunsemesters', TahunsemesterController::class)->middleware('auth');
Route::resource('/settings', SettingController::class)->middleware('auth');
Route::post('/updateSettings', [SettingController::class, 'updateSetting'])->middleware('auth');

Route::get('/selesai', [Controller::class, 'selesai'] )->middleware('auth')->name('selesai');
Route::get('/tutup', [Controller::class, 'tutup'] )->middleware('auth')->name('tutup');
Route::get('/edit-kuesioner',[Controller::class,'editKuesioner'])->middleware('auth')->name('editKuesioner');

Route::get('/dashboard-admin',[Controller::class,'dashboardAdmin'])->middleware('auth')->name('dashboardAdmin');

Route::get('/export/average',[Controller::class,'exportAverage'])->middleware('auth')->name('exportAverage');
Route::get('/show-score',[Controller::class,'showScore'])->middleware('auth')->name('showScore');
Route::get('/export/matkul/{matkul}',[Controller::class,'exportMatkul'])->middleware('auth')->name('exportMatkul');
Route::get('/export/layanan/{layanan}',[Controller::class,'exportLayanan'])->middleware('auth')->name('exportLayanan');


Route::get('/dashboard-comment', [Controller::class,'dashboardComment'])->middleware('auth')->name('dashboardComment');

Route::post('/dashboard-comment/{nimhs}', [Controller::class,'dashboardCommentStore'] )->name('dashboardComment.store');

require __DIR__ . '/auth.php';
