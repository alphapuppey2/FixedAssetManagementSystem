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
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\FiltersController;


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

    /*
    -------------------
            HOME
    -------------------
    */

    Route::get('/admin/home', [AsstController::class, 'assetCount'])->name('admin.home');

    /*
    -------------------
            USER
    -------------------
    */

    // USER LIST
    Route::get('/admin/user-list', [UserController::class, 'getUserList'])->name('userList');

    // USER UPDATES
    Route::put('/admin/user-update', [UserController::class, 'update'])->name('user.update');
    Route::patch('/admin/user-{id}', [UserController::class, 'delete'])->name('user.delete');
    Route::post('/user/{id}/reactivate', [UserController::class, 'reactivate'])->name('user.reactivate');

    // CREATE USER
    Route::post('/admin/users', [UserController::class, 'createUser'])->name('users.store');
    Route::get('/admin/user-create', function () {
        return view('admin.createUser');
    })->name('users.create');
    // USER FILTER LIST
    Route::get('/admin/users/filter', [FiltersController::class, 'filterUsers'])->name('filterUsers');
    
    /*
    -------------------
        ASSETS
    -------------------
    */

    // ASSET LIST
    Route::get('/admin/assets', [AsstController::class, 'showAllAssets'])->name('assetList');

    //ASSET LIST FILTER
    Route::get('/admin/assets/filter', [FiltersController::class, 'filterAssetsAdmin'])->name('admin.assets.filter');

    Route::get('admin/newasset', [AsstController::class, 'showForm'])->name('admin.newasset');
    Route::post('admin/asset/create', [AsstController::class, 'create'])->name('adminasset.create');
    Route::delete('admin/asset/delete/{id}', [AsstController::class, 'delete'])->name('adminasset.delete');

    Route::get('/fetch-department-data/{deptId}', [AsstController::class, 'fetchDepartmentData'])->name('fetch.department.data');

    // ASSET DETAIL
    Route::get('/admin/asset-details/{id}', [AsstController::class, 'showDetails'])->name('adminAssetDetails');
    Route::get('/admin/mntc-asset-details/{code}', [MaintenanceController::class, 'getAssetDetails'])->name('adminAssetsDetails.mntc');

    // ASSET UPDATES
    Route::put('admin/asset/edit/{id}', [AsstController::class, 'update'])->name('adminAssetDetails.edit');

    Route::delete('admin/assets/multi-delete', [AsstController::class, 'multiDelete'])->name('adminasset.multiDelete');

    //DISPOSE
    Route::post('admin/asset/dispose/{id}', [AsstController::class, 'dispose'])->name('adminAsset.dispose');

    /*
    -------------------
        MAINTENANCE
    -------------------
    */

    // MAINTENANCE REQUEST-APPROVE-DENY
    Route::get('admin/maintenance', [MaintenanceController::class, 'index'])->name('adminMaintenance');
    Route::get('admin/maintenance/approved', [MaintenanceController::class, 'approvedList'])->name('adminMaintenanceAproved');
    Route::get('admin/maintenance/denied', [MaintenanceController::class, 'deniedList'])->name('adminMaintenanceDenied');

    Route::post('admin/maintenance/{id}/approve', [MaintenanceController::class, 'approve'])->name('adminMaintenance.approve');
    Route::post('admin/maintenance/{id}/deny', [MaintenanceController::class, 'deny'])->name('adminMaintenance.deny');

    Route::get('admin/maintenance/{id}/editApproved', [MaintenanceController::class, 'editApproved'])->name('adminmaintenance.editApproved');
    Route::get('admin/maintenance/{id}/editDenied', [MaintenanceController::class, 'editDenied'])->name('adminmaintenance.editDenied');
    Route::put('admin/maintenance/{id}/updateDenied', [MaintenanceController::class, 'updateDenied'])->name('adminmaintenance.updateDenied');
    Route::put('admin/maintenance/{id}/updateApproved', [MaintenanceController::class, 'updateApproved'])->name('adminmaintenance.updateApproved');

    // MAINTENANCE COMPLETED-CANCELLED
    Route::get('/admin/maintenance/records', [MaintenanceController::class, 'showRecords'])->name('adminMaintenance.records');

    // MAINTENANCE PREVENTIVE-PREDICTIVE
    Route::get('/admin/maintenance_sched', [MaintenanceSchedController::class, 'showPreventive'])->name('adminMaintenance_sched');
    Route::get('/admin/maintenance_sched/predictive', [MaintenanceSchedController::class, 'showPredictive'])->name('adminMaintenance_sched.predictive');

    // INITIAL
    // -----------
    Route::put('/admin/preventive/{id}', [PreventiveMaintenanceController::class, 'update'])->name('adminpreventive.update');
    Route::get('/admin/preventive/{id}/edit', [PreventiveMaintenanceController::class, 'edit'])->name('adminpreventive.edit');

    Route::post('admin/run-maintenance-check', [PreventiveMaintenanceController::class, 'checkAndGenerate'])->name('admin.run-maintenance-check');
    Route::post('admin/reset-countdown', [PreventiveMaintenanceController::class, 'resetCountdown'])->name('admin.reset-countdown');
    Route::get('admin/predictive/analyze', [PredictiveController::class, 'analyze']);

    // CREATE MAINTENANCE
    Route::get('/admin/create-maintenance', [MaintenanceController::class, 'create'])->name('adminFormMaintenance');
    Route::post('/admin/create-maintenance', [MaintenanceController::class, 'store'])->name('adminMaintenance.store');

    /*
    -------------------
        ACTIVITY LOGS
    -------------------
    */

    // ACTIVITY LOGS
    Route::get('/admin/activity-logs', [ActivityLogController::class, 'index'])->name('admin.activity-logs');
    Route::get('/admin/activity-logs/export', [ActivityLogController::class, 'export'])->name('activityLogs.export');
    Route::post('/admin/activity-logs/settings', [ActivityLogController::class, 'updateSettings'])->name('activityLogs.updateSettings');

    /*
    -------------------
        SEARCH
    -------------------
    */

    // SEARCH
    Route::get('/admin/user-list/search', [SearchController::class, 'searchUser'])->name('searchUsers');
    Route::get('/admin/maintenance/search', [SearchController::class, 'searchMaintenance'])->name('adminMaintenanceSearch');
    route::get('/admin/assets/search', [SearchController::class, 'searchAssets'])->name('searchAssets');
    Route::get('/admin/activity-logs/search', [SearchController::class, 'searchActivityLogs'])->name('searchActivity');
    Route::get('/admin/maintenance-scheduling/search', [SearchController::class, 'searchPreventive'])->name('adminMaintenanceSchedSearch');
    Route::get('/admin/maintenance/records/search', [MaintenanceController::class, 'showRecords'])->name('adminMaintenanceRecords.search');

    /*
    -------------------
        PROFILE
    -------------------
    */

    // DETAILS
    Route::get('/admin/profile', function () {return view('admin.profile');})->name('admin.profile');
    Route::get('/admin/profile', [ProfileController::class, 'adminView'])->name('admin.profile');
    // UPDATE
    Route::patch('/admin/profile_update', [ProfileController::class, 'update'])->name('admin.profile_update');
    // CHANGE PASSWORD
    Route::get('/admin/profile_password', function () {return view('admin.profilePassword');})->name('admin.profile_password');
    Route::patch('/admin/profile_password', [ProfileController::class, 'changePassword'])->name('admin.changePassword');
});

// DeptHead Routes
Route::middleware(['deptHeadUserType', 'auth', 'verified'])->group(function () {

    /*
    -------------------
            HOME
    -------------------
    */

    Route::get('/dept_head/home', [AsstController::class, 'assetCount'])->name('dept_head.home');
    Route::get('/asset/graph', [AsstController::class, 'assetGraph'])->name('asset.graph');

    /*
    -------------------
            ASSET
    -------------------
    */

    // LIST ALL IN DEPARTMENT
    Route::get('/asset', [AsstController::class, 'showDeptAsset'])->name('asset');

    Route::get('/filter-assets', [FiltersController::class, 'filterAssets'])->name('asset.filter');

    // CREATE NEW
    Route::post('/asset', [AsstController::class, 'create'])->name('asset.create');
    // route::get('asset/graph', [AsstController::class, 'assetGraph'])->name('asset.graph');
    Route::get('asset/{id}', [AsstController::class, 'showDetails'])->name('assetDetails');
    Route::put('asset/edit/{id}', [AsstController::class, 'update'])->name('assetDetails.edit');
    Route::delete('asset/delete/{id}', [AsstController::class, 'delete'])->name('asset.delete');
    Route::get('/newasset', [AsstController::class, 'showForm'])->name('newasset');

    // DETAILS
    Route::get('/asset/{id}', [AsstController::class, 'showDetails'])->name('assetDetails');
    // MAINTENANCE HISTORY
    Route::get('asset/{id}/history', [AsstController::class, 'showHistory'])->name('asset.history');
    // ASSIGNED TO
    Route::get('/asset/user/autocomplete', [UserController::class, 'autocomplete'])->name('autocomplete');

    // UPDATE
    Route::put('/asset/edit/{id}', [AsstController::class, 'update'])->name('assetDetails.edit');
    Route::delete('/asset/delete/{id}', [AsstController::class, 'delete'])->name('asset.delete');
    Route::delete('/assets/multi-delete', [AsstController::class, 'multiDelete'])->name('asset.multiDelete');

    // IMPORT
    Route::get('/download-template', [AsstController::class, 'downloadCsvTemplate'])->name('download.csv.template');
    Route::post('/asset/upload-csv', [AsstController::class, 'uploadCsv'])->name('upload.csv');

    //DISPOSE
    Route::post('/asset/dispose/{id}', [AsstController::class, 'dispose'])->name('asset.dispose');


    /*
    -------------------
        MAINTENANCE
    -------------------
    */

    // LIST REQUEST-APPROVED-DENIED
    Route::get('/maintenance', [MaintenanceController::class, 'index'])->name('maintenance');
    Route::get('/maintenance/approved', [MaintenanceController::class, 'approvedList'])->name('maintenance.approved');
    Route::get('/maintenance/denied', [MaintenanceController::class, 'deniedList'])->name('maintenance.denied');

    // APPROVE-DENY
    Route::post('/maintenance/{id}/approve', [MaintenanceController::class, 'approve'])->name('maintenance.approve');
    Route::post('/maintenance/{id}/deny', [MaintenanceController::class, 'deny'])->name('maintenance.deny');

    // COMPLETED-CANCELLED
    Route::get('/maintenance/records', [MaintenanceController::class, 'showRecords'])->name('maintenance.records');

    // EDIT APPROVED-DENIED
    Route::get('/maintenance/{id}/editApproved', [MaintenanceController::class, 'editApproved'])->name('maintenance.editApproved');
    Route::get('/maintenance/{id}/editDenied', [MaintenanceController::class, 'editDenied'])->name('maintenance.editDenied');

    // UPDATE APPROVED-DENIED
    Route::put('/maintenance/{id}/updateDenied', [MaintenanceController::class, 'updateDenied'])->name('maintenance.updateDenied');
    Route::put('/maintenance/{id}/updateApproved', [MaintenanceController::class, 'updateApproved'])->name('maintenance.updateApproved');

    Route::get('/maintenance/download', [MaintenanceController::class, 'download'])->name('maintenance.download');

    // PREVENTIVE-PREDICTIVE
    Route::get('/maintenance_sched', [MaintenanceSchedController::class, 'showPreventive'])->name('maintenance_sched');
    Route::get('/maintenance_sched/predictive', [MaintenanceSchedController::class, 'showPredictive'])->name('maintenance_sched.predictive');

    // CREATE
    Route::get('/create-maintenance', [MaintenanceController::class, 'create'])->name('formMaintenance');
    Route::post('/create-maintenance', [MaintenanceController::class, 'store'])->name('maintenance.store');
    Route::get('/assets/details/{code}', [MaintenanceController::class, 'getAssetDetails'])->name('assets.details');

    // UPDATE
    Route::post('/update-maintenance-status', [MaintenanceController::class, 'updateStatus']);

    // PREVENTIVE
    Route::post('/run-maintenance-check', [PreventiveMaintenanceController::class, 'checkAndGenerate'])->name('run-maintenance-check');
    Route::post('/reset-countdown', [PreventiveMaintenanceController::class, 'resetCountdown'])->name('reset-countdown');
    Route::get('/preventive/{id}/edit', [PreventiveMaintenanceController::class, 'edit'])->name('preventive.edit');
    Route::put('/preventive/{id}', [PreventiveMaintenanceController::class, 'update'])->name('preventive.update');
    Route::get('/predictive/analyze', [PredictiveController::class, 'analyze']);

    /*
    -------------------
        SEARCH
    -------------------
    */

    Route::get('/maintenance/search', [SearchController::class, 'searchMaintenance'])->name('maintenance.search');
    Route::get('/maintenance-scheduling/search', [SearchController::class, 'searchPreventive'])->name('maintenanceSchedSearch');
    Route::get('/maintenance/filter', [SearchController::class, 'filterMaintenance'])->name('maintenance.filter');

    Route::get('/maintenance/records/search', [MaintenanceController::class, 'showRecords'])->name('maintenance.records.search');
    route::get('/asset/search/row', [AsstController::class, 'searchFiltering'])->name('assets.search');
    Route::get('asset/filteredsearch',[FiltersController::class , 'filterAssets'])->name('asset.filtered');

    /*
    -------------------
        SETTINGS
    -------------------
    */

    Route::get('/setting', [settingController::class, 'showSettings'])->name('setting');
    Route::post('/setting/{tab}', [settingController::class, 'store'])->name('setting.create');
    Route::delete('/setting/destroy/{tab}/{id}', [settingController::class, 'destroy'])->name('setting.delete');
    Route::put('/setting/update/{tab}/{id}', [settingController::class, 'updateSettings'])->name('setting.edit');

    /*
    -------------------
        PROFILE
    -------------------
    */

    // DETAILS
    Route::get('/dept_head/profile', function () {return view('dept_head.profile');})->name('profile');

    // UPDATE
    Route::patch('/dept_head/profile_update', [ProfileController::class, 'update'])->name('dept_head.profile_update');

    // CHANGE PASSWORD
    Route::get('/profile/change_password', function () {return view('dept_head.profile_password');})->name('dept_head.profile_password');
    Route::patch('/profile/change_password', [ProfileController::class, 'changePassword'])->name('profile.change_password');

    /*
    -------------------
        REPORTS
    -------------------
    */

    // ASSET REPORTS
    Route::get('/asset-report', [ReportsController::class, 'showAssetFilter'])->name('asset.report');
    Route::get('/generate-asset-report', [ReportsController::class, 'generateAssetReport'])->name('asset.report.generate');
    Route::get('/asset-report/download', [ReportsController::class, 'downloadAssetReport'])->name('asset.report.download');

    // MAINTENANCE REPORTS
    Route::get('/maintenance-report', [ReportsController::class, 'showMaintenanceFilter'])->name('maintenance.report');
    Route::get('/generate-maintenance-report', [ReportsController::class, 'generateMaintenanceReport'])->name('maintenance.report.generate');
    Route::get('/maintenance-report/download', [ReportsController::class, 'downloadMaintenanceReport'])->name('maintenance.report.download');
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

    Route::post('/validate-qr', [UserSideController::class, 'checkAssetCode'])->name('validate.qr');


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
