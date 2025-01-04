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

    // System
    Route::prefix('users/{users}')->group(function () {
        Route::get('/banned',[App\Http\Controllers\UserController::class, 'banned'])->name('users.banned');
        Route::get('/unbanned',[App\Http\Controllers\UserController::class, 'unbanned'])->name('users.unbanned');
    });

    Route::prefix('role/{roles}')->group(function () {
        Route::get('/permission',[App\Http\Controllers\RoleController::class, 'permission_index'])->name('role.permission.index');
        Route::post('/permission',[App\Http\Controllers\RoleController::class, 'permission_store'])->name('role.permission.store');
    });

    Route::resource('users', App\Http\Controllers\UserController::class);
    Route::resource('roles', App\Http\Controllers\RoleController::class);
    Route::resource('permissions', App\Http\Controllers\PermissionController::class);

    // Document
    Route::prefix('datawarga')->group(function () {
        Route::get('import',[App\Http\Controllers\DataWargaController::class, 'import_index'])->name('datawarga.import.index');
        Route::post('import',[App\Http\Controllers\DataWargaController::class, 'import_store'])->name('datawarga.import.store');
    });
    Route::resource('datawarga', App\Http\Controllers\DataWargaController::class);
    Route::resource('inforw', App\Http\Controllers\InfoRwController::class);
});
