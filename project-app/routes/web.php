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
use App\Http\Controllers\MaintenanceSchedController;
use App\Http\Controllers\RepairController;
use App\Http\Controllers\PreventiveMaintenanceController;
use App\Http\Controllers\PredictiveController;
use App\Http\Controllers\UserSideController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SearchController;

Route::get('/', function () {
    if (Auth::check()) {
        switch (Auth::user()->usertype) {
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
    Route::get('/asset/department', [departmentCtrl::class, 'index'])->name('department');
    Route::post('/asset/newdepartment', [departmentCtrl::class, 'create'])->name('newdepartment');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/{id}/delete', [NotificationController::class, 'delete'])->name('notifications.delete');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllRead');
    Route::post('/notifications/delete-all', [NotificationController::class, 'deleteAll'])->name('notifications.deleteAll');

    Route::get('/search', [SearchController::class, 'globalSearch'])->name('search.global');

    // Route::get('/notification', function () {
    //     return view('layouts.notification');
    // })->name('notification');

    Route::get('/send-test-email', [NotificationController::class, 'sendTestEmail']);
});

// Admin Routes
Route::middleware(['adminUserType', 'auth', 'verified'])->group(function () {

    Route::get('/admin/home', [AsstController::class, 'assetCount'])->name('admin.home');

    // USER LIST
    Route::get('/admin/user-list', [UserController::class, 'getUserList'])->name('userList');
    Route::get('/admin/user-list/search', [UserController::class, 'search'])->name('searchUsers');

    // USER UPDATES
    Route::put('/admin/user-update', [UserController::class, 'update'])->name('user.update');
    Route::patch('/admin/user-{id}', [UserController::class, 'delete'])->name('user.delete');
    Route::post('/user/{id}/reactivate', [UserController::class, 'reactivate'])->name('user.reactivate');

    // CREATE USER
    Route::post('/admin/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/admin/user-create', function () {
        return view('admin.createUser');
    })->name('users.create');

    // ASSET LIST
    Route::get('/admin/asset-list', [AsstController::class, 'showAllAssets'])->name('assetList');
    Route::get('/admin/assets/department/{dept}', [AsstController::class, 'showAssetsByDept'])->name('assetListByDept');
    route::get('/admin/assets/search', [AsstController::class, 'searchAssets'])->name('searchAssets');

    // ASSET DETAIL
    Route::get('/admin/asset-details/{id}', [AsstController::class, 'showDetails'])->name('adminAssetDetails');
    Route::get('/admin/mntc-asset-details/{code}', [MaintenanceController::class, 'getAssetDetails'])->name('adminAssetsDetails.mntc');

    // ASSET UPDATES
    Route::put('admin/asset/edit/{id}', [AsstController::class, 'update'])->name('adminAssetDetails.edit');

    // MAINTENANCE REQUEST-APPROVE-DENY
    Route::get('/admin/maintenance', [MaintenanceController::class, 'index'])->name('adminMaintenance');
    Route::get('/admin/maintenance/approved', [MaintenanceController::class, 'approvedList'])->name('adminMaintenanceAproved');
    Route::get('/admin/maintenance/denied', [MaintenanceController::class, 'deniedList'])->name('adminMaintenanceDenied');

    // MAINTENANCE PREVENTIVE-PREDICTIVE
    Route::get('/admin/maintenance_sched', [MaintenanceSchedController::class, 'showPreventive'])->name('adminMaintenance_sched');
    Route::get('/admin/maintenance_sched/predictive', [MaintenanceSchedController::class, 'showPredictive'])->name('adminMaintenance_sched.predictive');

    // CREATE MAINTENANCE
    Route::get('/admin/create-maintenance', [MaintenanceController::class, 'create'])->name('adminFormMaintenance');
    Route::post('/admin/create-maintenance', [MaintenanceController::class, 'store'])->name('adminMaintenance.store');

    // ADMIN PROFILE
    Route::get('/admin/profile', function () {
        return view('admin.profile');
    })->name('admin.profile');
    Route::get('/admin/profile', [ProfileController::class, 'adminView'])->name('admin.profile');
    Route::patch('/admin/profile_update', [ProfileController::class, 'update'])->name('admin.profile_update');
    Route::get('/admin/profile_password', function () {
        return view('admin.profilePassword');
    })->name('admin.profile_password');

    Route::patch('/admin/profile_password', [ProfileController::class, 'changePassword'])->name('admin.profile_password');
});

// DeptHead Routes
Route::middleware(['deptHeadUserType', 'auth', 'verified'])->group(function () {

    Route::get('/dept_head/home', [AsstController::class, 'assetCount'])->name('dept_head.home');

    Route::get('/asset', [AsstController::class, 'showDeptAsset'])->name('asset');
    Route::post('/asset', [AsstController::class, 'create'])->name('asset.create');
    route::get('asset/graph', [AsstController::class, 'assetGraph'])->name('asset.graph');
    Route::get('asset/{id}', [AsstController::class, 'showDetails'])->name('assetDetails');
    Route::put('asset/edit/{id}', [AsstController::class, 'update'])->name('assetDetails.edit');
    Route::delete('asset/delete/{id}', [AsstController::class, 'delete'])->name('asset.delete');
    Route::get('/newasset', [AsstController::class, 'showForm'])->name('newasset');
    route::get('/asset/search/row', [AsstController::class, 'searchFiltering'])->name('assets.search');
    route::get('asset/{id}/history', [AsstController::class, 'showHistory'])->name('asset.history');
    Route::get('/asset/user/autocomplete', [UserController::class, 'autocomplete'])->name('autocomplete');

    // IMPORT
    Route::get('/download-template', [AsstController::class, 'downloadCsvTemplate'])->name('download.csv.template');
    Route::post('/asset/upload-csv', [AsstController::class, 'uploadCsv'])->name('upload.csv');

    Route::get('/maintenance', [MaintenanceController::class, 'index'])->name('maintenance');
    Route::get('/maintenance/approved', [MaintenanceController::class, 'approvedList'])->name('maintenance.approved');
    Route::get('/maintenance/denied', [MaintenanceController::class, 'deniedList'])->name('maintenance.denied');
    Route::post('/maintenance/{id}/approve', [MaintenanceController::class, 'approve'])->name('maintenance.approve');
    Route::post('/maintenance/{id}/deny', [MaintenanceController::class, 'deny'])->name('maintenance.deny');

    // SHOWS COMPLETED AND CANCELLED MAINTENANCE
    Route::get('/maintenance/records', [MaintenanceController::class, 'showRecords'])->name('maintenance.records');
    Route::get('/maintenance/records/search', [MaintenanceController::class, 'showRecords'])->name('maintenance.records.search');

    Route::get('/maintenance/{id}/editApproved', [MaintenanceController::class, 'editApproved'])->name('maintenance.editApproved');
    Route::get('/maintenance/{id}/editDenied', [MaintenanceController::class, 'editDenied'])->name('maintenance.editDenied');
    Route::put('/maintenance/{id}/updateDenied', [MaintenanceController::class, 'updateDenied'])->name('maintenance.updateDenied');
    Route::put('/maintenance/{id}/updateApproved', [MaintenanceController::class, 'updateApproved'])->name('maintenance.updateApproved');

    // Route::get('/maintenance/refreshTable', [MaintenanceController::class, 'refreshTable'])->name('maintenance.refreshTable');

    Route::get('/maintenance/search', [MaintenanceController::class, 'search'])->name('maintenance.search');
    Route::get('/maintenance/download', [MaintenanceController::class, 'download'])->name('maintenance.download');

    Route::get('/maintenance_sched', [MaintenanceSchedController::class, 'showPreventive'])->name('maintenance_sched');
    Route::get('/maintenance_sched/predictive', [MaintenanceSchedController::class, 'showPredictive'])->name('maintenance_sched.predictive');

    Route::get('/create-maintenance', [MaintenanceController::class, 'create'])->name('formMaintenance');
    Route::post('/create-maintenance', [MaintenanceController::class, 'store'])->name('maintenance.store');
    Route::get('/assets/details/{code}', [MaintenanceController::class, 'getAssetDetails'])->name('assets.details');

    Route::post('/run-maintenance-check', [PreventiveMaintenanceController::class, 'checkAndGenerate'])->name('run-maintenance-check');
    Route::post('/reset-countdown', [PreventiveMaintenanceController::class, 'resetCountdown'])->name('reset-countdown');

    Route::get('/preventive/{id}/edit', [PreventiveMaintenanceController::class, 'edit'])->name('preventive.edit');
    Route::put('/preventive/{id}', [PreventiveMaintenanceController::class, 'update'])->name('preventive.update');
    Route::post('/update-maintenance-status', [MaintenanceController::class, 'updateStatus']);

    Route::get('/predictive/analyze', [PredictiveController::class, 'analyze']);

    //setting page
    Route::get('/setting', [settingController::class, 'showSettings'])->name('setting');
    Route::post('/setting/{tab}', [settingController::class, 'store'])->name('setting.create');
    Route::delete('/setting/destroy/{tab}/{id}', [settingController::class, 'destroy'])->name('setting.delete');
    Route::put('/setting/update/{tab}/{id}', [settingController::class, 'updateSettings'])->name('setting.edit');

    Route::get('/profile', function () {
        return view('dept_head.profile');
    })->name('profile');

    Route::patch('/dept_head/profile_update', [ProfileController::class, 'update'])->name('dept_head.profile_update');

    Route::get('/profile/change_password', function () {
        return view('dept_head.profile_password');
    })->name('dept_head.profile_password');

    Route::patch('/profile/change_password', [ProfileController::class, 'changePassword'])->name('profile.change_password');

    //Reports
    Route::get('/custom-report', [ReportsController::class, 'show'])->name('custom.report');
    Route::get('/generate-custom-report', [ReportsController::class, 'generate'])->name('custom.report.generate');
    Route::get('/report/download', [ReportsController::class, 'downloadReport'])->name('report.download');

});

// User Routes
Route::middleware(['workerUserType', 'auth', 'verified'])->group(function () {
    Route::get('/user/home', function () {
        return view('user.home');
    })->name('user.home');

    Route::get('/user/scanQR', function () {
        return view('user.scanQR');
    })->name('user.scanQR');

    route::post('/maintenance/create', [UserSideController::class, 'createRequest'])->name('maintenance.create');

    Route::get('/assetdetails/{code}', [UserSideController::class, 'showDetails'])->name('qr.asset.details');

    route::get('/requests/list', [UserSideController::class, 'showRequestList'])->name('requests.list');

    route::post('/requests/cancel/{id}', [UserSideController::class, 'cancelRequest'])->name('requests.cancel');


    //PROFFILE SECTION
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



require __DIR__ . '/auth.php';
