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

// Route::get('/',[AsstController::class,'assetCount'])->middleware(['auth', 'verified'])->name('dashboard');

    // Route::get('/asset', [AsstController::class,'show'])->middleware(['auth', 'verified'])->name('asset');
    // Route::get('/asset', [AsstController::class,''])->middleware(['auth', 'verified'])->name('asset');


Route::middleware('auth')->group(function () {

    // Route::post('/maintenance', [AsstController::class,'create'])->name('maintenance.create');

    Route::get('/asset/department', [departmentCtrl::class,'index'])->name('department');
    Route::post('/asset/newdepartment', [departmentCtrl::class,'create'])->name('newdepartment');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Routes
Route::middleware(['adminUserType','auth', 'verified'])->group(function(){
    Route::get('/admin/home', function () {
        return view('admin.home');
    })->name('admin.home');



});
// DeptHead Routes
Route::middleware(['deptHeadUserType','auth', 'verified'])->group(function(){

    Route::get('/dept_head/home', [AsstController::class , 'assetCount'])->name('dept_head.home');

    Route::get('/asset', [AsstController::class,'show'])->name('asset');
    Route::get('/newasset', [AsstController::class,'showForm'])->name('newasset');
    Route::post('/asset', [AsstController::class,'create'])->name('asset.create');

    Route::get('/maintenance', [maintenance::class,'show'])->name('maintenance');
    Route::get('/createmaintenance', [maintenance::class,'showForm'])->name('formMaintenance');

    Route::get('/manufacturer', function () {
        return view('manufacturer');
    })->name('manufacturer');
    Route::get('/setting', function () {
        return view('setting');
    })->name('setting');
    Route::get('/report', function () {
        return view('reports');
    })->name('report');

});
// User Routes
Route::middleware(['workerUserType','auth', 'verified'])->group(function(){
    Route::get('/user/home', function () {
        return view('user.home');
    })->name('user.home');

});




require __DIR__.'/auth.php';
