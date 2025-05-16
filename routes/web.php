<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\OtRequestController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\AdminController;


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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/dashboard', [LeaveRequestController::class, 'dashboard'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('/', [LeaveRequestController::class, 'dashboard'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //Request
    Route::get('/requests', [LeaveRequestController::class, 'index'])->name('requests.index');
    Route::get('/requests/create', [LeaveRequestController::class, 'create'])->name('requests.create');
    Route::post('/requests', [LeaveRequestController::class, 'store'])->name('requests.store');

    Route::post('/requests/{request}/approve', [LeaveRequestController::class, 'approve'])->name('requests.approve');
    Route::post('/requests/{request}/reject', [LeaveRequestController::class, 'reject'])->name('requests.reject');

    //Checkin
    Route::post('/check-in', [AttendanceController::class, 'store'])->name('attendance.store');
    //Get Status
    Route::get('/attendance/status', [AttendanceController::class, 'status'])->name('attendance.status');
    //Get lịch sử chấm công
    Route::get('/attendance-history', [AttendanceController::class, 'getAttendanceHistory']);
    //Tải xuống lịch sử chấm công
    Route::get('/admin/attendance/export', [AttendanceController::class, 'exportExcel'])->name('attendance.export.excel');
    //Xin OT
    Route::post('/ot-request', [OtRequestController::class, 'store'])->name('ot-request.store');

    //History with type
    Route::get('/history-data', [HistoryController::class, 'get']);
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.panel');

});

Route::middleware(['auth', 'is_admin'])->group(function () {
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
});


require __DIR__.'/auth.php';
