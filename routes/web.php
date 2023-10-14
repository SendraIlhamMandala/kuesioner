<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Models\Kuesioner;
use App\Models\Mahasiswa;
use App\Models\Trkuesk;
use App\Models\Trkuesl;
use App\Models\User;

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
    return view('welcome');
});

Route::get('/dashboard', [Controller::class, 'index'])->name('dashboard');

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

Route::post('/dashboard/{nimhs}', function (Request $request, $nimhs) {


    $updates_sl = [];

    foreach ($request['skor_sl'] as $kode => $kelas) {
        foreach ($kelas as $kelas_key => $skor) {
            $updateData = [
                'skor' => $skor
            ];

            $updates_sl[] = [
                'nimhs' => $nimhs,
                'kdkues' => $kode,
                'klkues' => $kelas_key,
                'updateData' => $updateData
            ];
        }
    }

    foreach ($updates_sl as $update) {
        Trkuesl::where('nimhs', $update['nimhs'])
            ->where('kdkues', $update['kdkues'])
            ->where('klkues', $update['klkues'])
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
                'updateData' => $updateData
            ];
        }
    }

    foreach ($updates_sk as $update) {
        Trkuesk::where('nimhs', $update['nimhs'])
            ->where('kdkues', $update['kdkues'])
            ->where('kdkmk', $update['kdkmk'])
            ->update($update['updateData']);
    }
    return redirect('/dashboard');
})->name('kuesionerDashboard.store');


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

require __DIR__ . '/auth.php';