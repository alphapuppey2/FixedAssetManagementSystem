<?php

use App\Http\Controllers\AsstController;
use App\Http\Controllers\departmentCtrl;
use App\Http\Controllers\maintenance;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\sideBarUserController;
use App\Http\Controllers\UserController;

Route::get('/', function(){
    if(Auth::check()){
        switch(Auth::user()->usertype){
            case 'admin':
                return redirect()->route('admin.home');
            case 'dept_head':
                return redirect()->route('dept_head.home');
            case 'user':
                return redirect()->route('user.home');
        }
    }
    return redirect('/login');
});

Route::middleware('auth')->group(function () {
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

Route::get('/userList', [UserController::class, 'getUserList'])->name('userList');
Route::put('/user/update', [UserController::class, 'update'])->name('user.update');
Route::delete('/user/{id}', [UserController::class, 'delete'])->name('user.delete');

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
        return view('dept_head.manufacturer');
    })->name('manufacturer');
    Route::get('/setting', function () {
        return view('dept_head.setting');
    })->name('setting');
    Route::get('/report', function () {
        return view('dept_head.reports');
    })->name('report');

});

// User Routes
Route::middleware(['workerUserType','auth', 'verified'])->group(function(){
    Route::get('/user/home', function () {
        return view('user.home');
    })->name('user.home');

    Route::get('user/scanQR', [sideBarUserController::class, 'scanQR'])->name('scanQR');
    Route::get('user/requestList', [sideBarUserController::class, 'requestList'])->name('requestList');
    Route::get('user/notification', [sideBarUserController::class, 'notification'])->name('notification');
    Route::get('user/profile', [sideBarUserController::class, 'profile'])->name('profile');

});

require __DIR__.'/auth.php';
