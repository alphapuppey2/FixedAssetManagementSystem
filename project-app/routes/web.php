<?php

use App\Http\Controllers\AsstController;
use App\Http\Controllers\departmentCtrl;
use App\Http\Controllers\maintenance;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/',[AsstController::class,'assetCount'])->middleware(['auth', 'verified'])->name('dashboard');

    // Route::get('/asset', [AsstController::class,'show'])->middleware(['auth', 'verified'])->name('asset');
    // Route::get('/asset', [AsstController::class,''])->middleware(['auth', 'verified'])->name('asset');
    Route::get('/maintenance', function () {
        return view('maintenance');
    })->middleware(['auth', 'verified'])->name('maintenance');
    Route::get('/manufacturer', function () {
        return view('manufacturer');
    })->middleware(['auth', 'verified'])->name('manufacturer');
    Route::get('/setting', function () {
        return view('setting');
    })->middleware(['auth', 'verified'])->name('setting');
    Route::get('/report', function () {
        return view('reports');
    })->middleware(['auth', 'verified'])->name('report');

Route::middleware('auth')->group(function () {


    Route::get('/asset', [AsstController::class,'show'])->name('asset');
    Route::get('/newasset', [AsstController::class,'showForm'])->name('newasset');
    Route::post('/asset', [AsstController::class,'create'])->name('asset.create');

    Route::get('/maintenance', [maintenance::class,'show'])->name('maintenance');
    Route::get('/createmaintenance', [maintenance::class,'showForm'])->name('formMaintenance');
    // Route::post('/maintenance', [AsstController::class,'create'])->name('maintenance.create');

    Route::get('/asset/department', [departmentCtrl::class,'index'])->name('department');
    Route::post('/asset/newdepartment', [departmentCtrl::class,'create'])->name('newdepartment');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin landing page
Route::get('/admin/home', function () {
    return view('admin.home');
})->middleware(['auth', 'verified'])->name('admin.home');

// DeptHead landing pages by department
Route::get('/dept_head/it/home', function () {
    return view('dept_head.it.home');
})->middleware(['auth', 'verified'])->name('dept_head.it.home');

Route::get('/dept_head/sales/home', function () {
    return view('dept_head.sales.home');
})->middleware(['auth', 'verified'])->name('dept_head.sales.home');

Route::get('/dept_head/fleet/home', function () {
    return view('dept_head.fleet.home');
})->middleware(['auth', 'verified'])->name('dept_head.fleet.home');

Route::get('/dept_head/production/home', function () {
    return view('dept_head.production.home');
})->middleware(['auth', 'verified'])->name('dept_head.production.home');

// User landing page
Route::get('/user/home', function () {
    return view('user.home');
})->middleware(['auth', 'verified'])->name('user.home');

// Default landing page for unmatched cases
Route::get('/default/home', function () {
    return view('default.home');
})->middleware(['auth', 'verified'])->name('default.home');

require __DIR__.'/auth.php';
