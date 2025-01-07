<?php

use Illuminate\Support\Facades\Route;

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
    return view('home');
});

Route::middleware(['web','auth','verified','banned'])->group(function () {
    Route::get('/dashboard',[App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('users/{users}')->group(function () {
        Route::get('/banned',[App\Http\Controllers\UserController::class, 'banned'])->name('users.banned');
        Route::get('/unbanned',[App\Http\Controllers\UserController::class, 'unbanned'])->name('users.unbanned');
    });

    Route::prefix('role/{roles}')->group(function () {
        Route::get('/permission',[App\Http\Controllers\RoleController::class, 'permission_index'])->name('role.permission.index');
        Route::post('/permission',[App\Http\Controllers\RoleController::class, 'permission_store'])->name('role.permission.store');
    });

    Route::prefix('datawarga')->group(function () {
        Route::get('import',[App\Http\Controllers\DataWargaController::class, 'import_index'])->name('datawarga.import.index');
        Route::post('import',[App\Http\Controllers\DataWargaController::class, 'import_store'])->name('datawarga.import.store');

        Route::post('{user}/pilih_rt',[App\Http\Controllers\DataWargaController::class, 'pilih_rt'])->name('datawarga.pilih.rt');
        Route::post('{user}/pilih_rw',[App\Http\Controllers\DataWargaController::class, 'pilih_rw'])->name('datawarga.pilih.rw');
    });
    Route::prefix('laporrw')->group(function () {
        Route::get('dibaca/{laporrw:slug}',[App\Http\Controllers\LaporRwController::class, 'dibaca'])->name('laporrw.dibaca');
    });

    Route::prefix('surveirw')->group(function () {
        Route::get('/',[App\Http\Controllers\SurveiController::class, 'index'])->name('surveirw.index');
        Route::get('/create',[App\Http\Controllers\SurveiController::class, 'create'])->name('surveirw.create');
        Route::post('/store',[App\Http\Controllers\SurveiController::class, 'store'])->name('surveirw.store');
        Route::prefix('{surveirw}')->group(function () {
            Route::get('/edit',[App\Http\Controllers\SurveiController::class, 'edit'])->name('surveirw.edit');
            Route::put('/update',[App\Http\Controllers\SurveiController::class, 'update'])->name('surveirw.update');
            Route::delete('/destroy',[App\Http\Controllers\SurveiController::class, 'destroy'])->name('surveirw.destroy');

            Route::prefix('pertanyaan')->group(function () {
                Route::get('/',[App\Http\Controllers\SurveiPertanyaanController::class, 'index'])->name('surveirw.pertanyaan.index');
                Route::get('/create',[App\Http\Controllers\SurveiPertanyaanController::class, 'create'])->name('surveirw.pertanyaan.create');
                Route::post('/store',[App\Http\Controllers\SurveiPertanyaanController::class, 'store'])->name('surveirw.pertanyaan.store');

                Route::prefix('{surveipertanyaan}')->group(function () {
                    Route::get('/edit',[App\Http\Controllers\SurveiPertanyaanController::class, 'edit'])->name('surveirw.pertanyaan.edit');
                    Route::put('/update',[App\Http\Controllers\SurveiPertanyaanController::class, 'update'])->name('surveirw.pertanyaan.update');
                    Route::delete('/destroy',[App\Http\Controllers\SurveiPertanyaanController::class, 'destroy'])->name('surveirw.pertanyaan.destroy');
                });
            });
        });
        Route::prefix('{survei:slug}')->group(function () {
            Route::prefix('warga')->group(function () {
                Route::get('/',[App\Http\Controllers\SurveiController::class, 'warga'])->name('surveirw.warga');
                Route::get('/{users:nik}/jawaban',[App\Http\Controllers\SurveiController::class, 'warga_jawaban'])->name('surveirw.warga_jawaban');
            });
            Route::prefix('jawaban')->group(function () {
                Route::get('/',[App\Http\Controllers\SurveiJawabanController::class, 'index'])->name('surveirw.jawaban.index');
                Route::post('/',[App\Http\Controllers\SurveiJawabanController::class, 'store'])->name('surveirw.jawaban.store');
            });
        });
    });

    Route::prefix('layanansurat')->group(function () {
        Route::get('/',[App\Http\Controllers\LayananSuratController::class, 'index'])->name('layanansurat.index');
        Route::get('/create',[App\Http\Controllers\LayananSuratController::class, 'create'])->name('layanansurat.create');
        Route::post('/store',[App\Http\Controllers\LayananSuratController::class, 'store'])->name('layanansurat.store');
        Route::post('/tujuan',[App\Http\Controllers\LayananSuratController::class, 'tujuan'])->name('layanansurat.tujuan');
        Route::prefix('{layanansurat}')->group(function () {
            Route::get('/edit',[App\Http\Controllers\LayananSuratController::class, 'edit'])->name('layanansurat.edit');
            Route::put('/update',[App\Http\Controllers\LayananSuratController::class, 'update'])->name('layanansurat.update');
            Route::delete('/destroy',[App\Http\Controllers\LayananSuratController::class, 'destroy'])->name('layanansurat.destroy');

            Route::prefix('nomorrt')->group(function () {
                Route::get('/',[App\Http\Controllers\LayananSuratController::class, 'nomorrt_index'])->name('layanansurat.nomorrt.index');
                Route::post('/',[App\Http\Controllers\LayananSuratController::class, 'nomorrt_store'])->name('layanansurat.nomorrt.store');
            });

            Route::prefix('nomorrw')->group(function () {
                Route::get('/',[App\Http\Controllers\LayananSuratController::class, 'nomorrw_index'])->name('layanansurat.nomorrw.index');
                Route::post('/',[App\Http\Controllers\LayananSuratController::class, 'nomorrw_store'])->name('layanansurat.nomorrw.store');
            });

            Route::get('print',[App\Http\Controllers\LayananSuratController::class, 'print'])->name('layanansurat.print');
        });
    });

    Route::resource('users', App\Http\Controllers\UserController::class);
    Route::resource('roles', App\Http\Controllers\RoleController::class);
    Route::resource('permissions', App\Http\Controllers\PermissionController::class);
    Route::resource('datawarga', App\Http\Controllers\DataWargaController::class);
    Route::resource('inforw', App\Http\Controllers\InfoRwController::class);

    Route::resource('kategoriberita', App\Http\Controllers\KategoriBeritaController::class);
    Route::resource('berita', App\Http\Controllers\BeritaController::class);

    Route::resource('laporrw', App\Http\Controllers\LaporRwController::class);
    Route::resource('tanyarw', App\Http\Controllers\TanyaRwController::class);
    Route::resource('lokerrw', App\Http\Controllers\LokerRwController::class);
});
