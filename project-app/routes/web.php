<?php

use App\Http\Controllers\AsstController;
use App\Http\Controllers\departmentCtrl;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', function () {
    // if(!Auth::user()) {
    //     return redirect('login');
    // }
    // $user = Auth::user();
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

    // Route::get('/asset', [AsstController::class,'show'])->middleware(['auth', 'verified'])->name('asset');
    // Route::get('/asset', [AsstController::class,''])->middleware(['auth', 'verified'])->name('asset');
    Route::get('/maintenance', function () {
        return view('maintenance');
    })->middleware(['auth', 'verified'])->name('maintenance');
    Route::get('/manufacturer', function () {
        return view('manufacturer');
    })->middleware(['auth', 'verified'])->name('manufacturer');
    Route::get('/setting', function () {
        return view('asset');
    })->middleware(['auth', 'verified'])->name('setting');
    Route::get('/report', function () {
        return view('reports');
    })->middleware(['auth', 'verified'])->name('report');

Route::middleware('auth')->group(function () {
    Route::get('/asset', [AsstController::class,'show'])->name('asset');
    Route::get('/asset/newasset', [AsstController::class,'showForm'])->name('newasset');
    Route::get('/asset/department', [departmentCtrl::class,'index'])->name('department');
    Route::post('/asset/newdepartment', [departmentCtrl::class,'create'])->name('newdepartment');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
