<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HrController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\ScheduleController;


// Auth
    Route::get('/', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// HR Registration
    // Show HR Registration Form
    Route::get('/register', [AuthController::class, 'showHrRegister'])->name('register');

    // Store HR Registration
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');

// HR Forgot Password
    Route::get('/forgot-password', [AuthController::class, 'showForgot']);
    Route::post('/forgot-password', [AuthController::class, 'resetPassword']);

// Admin
    Route::middleware(['auth.admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);
    Route::get('/admin/hr-approvals', [AdminController::class, 'showPendingHR']);
    Route::post('/admin/hr-approvals/{id}/approve', [AdminController::class, 'approve']);
    Route::post('/admin/hr-approvals/{id}/reject', [AdminController::class, 'reject']);
    Route::get('/admin/approve-hr/{id}', [AdminController::class, 'approveHr'])->name('admin.approve.hr');
    Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
    Route::post('/admin/login', [AdminAuthController::class, 'login']);});

// HR
    Route::middleware(['auth.hr'])->group(function () {
        Route::get('/hr/dashboard', [HrController::class, 'dashboard'])->name('hr.dashboard');
        Route::get('/hr/employees', [HrController::class, 'employeeIndex'])->name('employees.index');
        Route::get('/hr/employee/add', [HrController::class, 'showAddEmployeeForm'])->name('employees.add.form');
        Route::post('/hr/employee/add', [HrController::class, 'addEmployee'])->name('employees.add');
        Route::get('/hr/employee/view/{id}', [HrController::class, 'viewEmployee'])->name('employees.view');
        Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');
        Route::post('/calendar', [CalendarController::class, 'store'])->name('calendar.store');
        Route::put('/calendar/{id}', [CalendarController::class, 'update'])->name('calendar.update');
        Route::delete('/calendar/{id}', [CalendarController::class, 'destroy'])->name('calendar.destroy');        
        Route::resource('calendar', CalendarController::class);
        Route::post('/calendar/reset', [CalendarController::class, 'reset'])->name('calendar.reset');
        Route::resource('schedule', ScheduleController::class);
        Route::post('/schedule/save', [ScheduleController::class, 'save'])->name('schedule.save');
        Route::get('hr/employee/{id}/edit', [HrController::class, 'edit'])->name('employees.edit');
        Route::post('/employee/{id}/update', [HrController::class, 'update'])->name('employees.update');
        Route::post('/hr/employee/{employee}/schedules', [HrController::class, 'storeSchedule'])
        ->name('employees.schedules.store');
    });

    Route::prefix('schedule')->group(function () {
        Route::get('/{id}/view', [ScheduleController::class, 'view'])->name('schedule.view');
        Route::get('/{id}/print', [ScheduleController::class, 'print'])->name('schedule.print');
        Route::get('/{id}/download', [ScheduleController::class, 'download'])->name('schedule.download');
    });

    Route::get('/test-pdf', function () {
        $pdf = PDF::loadView('test-pdf', ['name' => 'Wendy']);
        return $pdf->download('sample.pdf');
    });

