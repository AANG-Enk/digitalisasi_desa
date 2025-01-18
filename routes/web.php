<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

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
    Route::post('/notifications/read', function (Request $request) {
        $notification = auth()->user()->notifications()->find($request->id);
        if ($notification) {
            $notification->markAsRead();
        }
        return redirect()->back();
    })->name('notifications.read');

    Route::post('/notifications/all/read',function(){
        $user = auth()->user();
        $user->unreadNotifications->markAsRead();
        return redirect()->back();
    })->name('notifications.read.all');

    Route::get('/dashboard',[App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('profile')->group(function () {
        Route::get('/',[App\Http\Controllers\ProfileController::class, 'index'])->name('profile.index');
        Route::post('/',[App\Http\Controllers\ProfileController::class, 'store'])->name('profile.store');
        Route::post('/upload',[App\Http\Controllers\ProfileController::class,'upload'])->name('profile.upload');
        Route::get('/settings',[App\Http\Controllers\ProfileController::class, 'setting'])->name('profile.setting');
    });

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
        Route::get('import/status',[App\Http\Controllers\DataWargaController::class, 'checkImportStatus'])->name('datawarga.import.status');

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

    Route::prefix('forumrw')->group(function () {
        route::get('/saya',[App\Http\Controllers\ForumController::class, 'saya'])->name('forum.saya');
    });

    Route::prefix('forumpengurusrw')->group(function () {
        Route::get('/',[App\Http\Controllers\ForumController::class, 'index_pengurus'])->name('forumrw.pengurus.index');
        route::get('/saya',[App\Http\Controllers\ForumController::class, 'saya_pengurus'])->name('forum.pengurus.saya');
        Route::get('/create',[App\Http\Controllers\ForumController::class, 'create_pengurus'])->name('forumrw.pengurus.create');
        Route::post('/store',[App\Http\Controllers\ForumController::class, 'store_pengurus'])->name('forumrw.pengurus.store');
        Route::prefix('{forumrw}')->group(function () {
            Route::get('/',[App\Http\Controllers\ForumController::class, 'show_pengurus'])->name('forumrw.pengurus.show');
            Route::get('/edit',[App\Http\Controllers\ForumController::class, 'edit_pengurus'])->name('forumrw.pengurus.edit');
            Route::put('/update',[App\Http\Controllers\ForumController::class, 'update_pengurus'])->name('forumrw.pengurus.update');
            Route::delete('/destroy',[App\Http\Controllers\ForumController::class, 'destroy_pengurus'])->name('forumrw.pengurus.destroy');
        });
    });

    Route::prefix('ireda')->group(function () {
        Route::prefix('{donasi:slug}')->group(function () {
            Route::get('/daftar',[App\Http\Controllers\BayarDonasiController::class, 'index'])->name('ireda.iuran.index');
            Route::get('/iuran',[App\Http\Controllers\BayarDonasiController::class, 'create'])->name('ireda.iuran.create');
            Route::post('/iuran',[App\Http\Controllers\BayarDonasiController::class, 'store'])->name('ireda.iuran.store');
            Route::prefix('{bayardonasi}/bukti')->group(function () {
                Route::get('/',[App\Http\Controllers\BayarDonasiController::class, 'bukti_index'])->name('ireda.iuran.bukti.index');
                Route::post('/',[App\Http\Controllers\BayarDonasiController::class, 'bukti_store'])->name('ireda.iuran.bukti.store');
                Route::post('/verifikasi',[App\Http\Controllers\BayarDonasiController::class, 'verifikasi'])->name('ireda.iuran.verifikasi');
            });
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
    Route::resource('forumrw', App\Http\Controllers\ForumController::class);
    Route::resource('tanirw', App\Http\Controllers\TaniRwController::class);
    Route::resource('ireda', App\Http\Controllers\DonasiController::class);
    Route::resource('pasarrw', App\Http\Controllers\PasarRwController::class);
    Route::resource('adsrw', App\Http\Controllers\IklanRwController::class);
});
