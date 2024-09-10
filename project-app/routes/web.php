<?php

use App\Http\Controllers\AsstController;
use App\Http\Controllers\departmentCtrl;
use App\Http\Controllers\maintenance;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\settingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\QRUserController;
use App\Http\Controllers\RepairController;

Route::get('/', function(){
    if(Auth::check()){
        switch(Auth::user()->usertype){
            case 'admin':
                return redirect()->route('admin.home');
            case 'dept_head':
                return redirect()->route('dept_head.home');
            case 'user':
                return redirect()->route('user.scanQR');
        }
    }
    return redirect('/login');
});

Route::middleware('auth')->group(function () {
    Route::get('/asset/department', [departmentCtrl::class,'index'])->name('department');
    Route::post('/asset/newdepartment', [departmentCtrl::class,'create'])->name('newdepartment');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

// Admin Routes
Route::middleware(['adminUserType','auth', 'verified'])->group(function(){
    Route::get('/admin/home', function () {
        return view('admin.home');
    })->name('admin.home');

    Route::get('/admin/user-list', [UserController::class, 'getUserList'])->name('userList');
    Route::put('/admin/user-update', [UserController::class, 'update'])->name('user.update');
    Route::delete('/admin/user-{id}', [UserController::class, 'delete'])->name('user.delete');
    Route::get('/admin/user-list/search', [UserController::class, 'search'])->name('searchUsers');
    Route::post('/admin/users', [UserController::class, 'store'])->name('users.store');

    Route::get('/admin/user-create', function () {
        return view('admin.create-user');
    })->name('users.create');


    Route::get('/admin/profile', function () {
        return view('admin.profile');
    })->name('admin.profile');

    Route::get('/admin/profile', [ProfileController::class, 'adminView'])->name('admin.profile');
    Route::patch('/admin/profile_update', [ProfileController::class, 'update'])->name('admin.profile_update');

    Route::get('/admin/profile_password', function () {
        return view('admin.profile_password');
    })->name('admin.profile_password');

    Route::patch('/admin/profile_password', [ProfileController::class, 'changePassword'])->name('admin.profile_password');

});

// DeptHead Routes
Route::middleware(['deptHeadUserType','auth', 'verified'])->group(function(){

    Route::get('/dept_head/home', [AsstController::class , 'assetCount'])->name('dept_head.home');

    Route::get('/asset', [AsstController::class,'show'])->name('asset');
    Route::post('/asset', [AsstController::class,'create'])->name('asset.create');
    Route::get('asset/{id}',[AsstController::class,'showDetails'])->name('assetDetails');
    Route::put('asset/edit/{id}',[AsstController::class,'update'])->name('assetDetails.edit');
    Route::delete('asset/delete/{id}',[AsstController::class,'delete'])->name('asset.delete');
    Route::get('/newasset', [AsstController::class,'showForm'])->name('newasset');

    // Route::get('/maintenance', [maintenance::class,'show'])->name('maintenance');
    // Route::get('/maintenance', [MaintenanceController::class, 'index'])->name('maintenance');
    Route::get('/maintenance', [MaintenanceController::class, 'index'])->name('maintenance');
    Route::get('/maintenance/approved', [MaintenanceController::class, 'approved'])->name('maintenance.approved');
    Route::get('/maintenance/denied', [MaintenanceController::class, 'denied'])->name('maintenance.denied');

    Route::post('/maintenance/{id}/approve', [MaintenanceController::class, 'approve'])->name('maintenance.approve');
    Route::post('/maintenance/{id}/deny', [MaintenanceController::class, 'deny'])->name('maintenance.deny');

    Route::get('/maintenance/search', [MaintenanceController::class, 'search'])->name('maintenance.search');

    Route::get('/maintenance/download', [MaintenanceController::class, 'download'])->name('maintenance.download');

    Route::get('/createmaintenance', [maintenance::class,'showForm'])->name('formMaintenance');

    Route::get('/manufacturer', function () {
        return view('dept_head.manufacturer');
    })->name('manufacturer');
    Route::get('/setting',[ settingController::class , 'showSettings'])->name('setting');
    Route::get('/report', function () {
        return view('dept_head.reports');
    })->name('report');

    Route::get('/profile', function () {
        return view('dept_head.profile');
    })->name('profile');

    Route::patch('/dept_head/profile_update', [ProfileController::class, 'update'])->name('dept_head.profile_update');

    Route::get('/profile/change_password', function () {
        return view('dept_head.profile_password');
    })->name('dept_head.profile_password');

    Route::patch('/profile/change_password', [ProfileController::class, 'changePassword'])->name('profile.change_password');

});

// User Routes
Route::middleware(['workerUserType','auth', 'verified'])->group(function(){
    Route::get('/user/home', function () {
        return view('user.home');
    })->name('user.home');

    Route::get('/user/scanQR', function () {
        return view('user.scanQR');
    })->name('user.scanQR');

    route::post('/repair-request', [RepairController::class, 'store'])->name('repair.request');

    Route::get('/assetdetails/{code}', [QRUserController::class, 'showDetails'])->name('qr.asset.details');

    Route::get('/user/requestList', [MaintenanceController::class, 'index'])->name('user.requestList');

    Route::get('/requests', [AsstController::class, 'showRequestList'])->name('requests.list');

    Route::get('/user/notification', function () {
        return view('user.notification');
    })->name('user.notification');
    
    Route::get('/user/profile', function () {
        return view('user.profile');
    })->name('user.profile');
    
    Route::patch('/user/profile_update', [ProfileController::class, 'update'])->name('user.profile_update');

    Route::get('/user/profile_edit', function () {
        return view('user.profile_edit');
    })->name('user.profile_edit');

    Route::get('/user/profile_password', function () {
        return view('user.profile_password');
    })->name('user.profile_password');

    Route::patch('/user/password', [ProfileController::class, 'changePassword'])->name('user.changePassword');
});


Route::get('back', function () {
    return redirect()->back();
})->name('back');



require __DIR__.'/auth.php';
