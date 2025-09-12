<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\HrController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EmployeeScheduleController;
use App\Http\Controllers\DeductionSettingController;

    // ======================
    // AUTH ROUTES
    // ======================
    Route::get('/', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

    // HR Registration
    Route::get('/register', [AuthController::class, 'showHrRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');

    // HR Forgot Password
    Route::get('/forgot-password', [AuthController::class, 'showForgot']);
    Route::post('/forgot-password', [AuthController::class, 'resetPassword']);

    // ======================
    // ADMIN ROUTES
    // ======================
    Route::middleware(['auth.admin'])->group(function () {
        Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);
        Route::get('/admin/hr-approvals', [AdminController::class, 'showPendingHR']);
        Route::post('/admin/hr-approvals/{id}/approve', [AdminController::class, 'approve']);
        Route::post('/admin/hr-approvals/{id}/reject', [AdminController::class, 'reject']);
        Route::get('/admin/approve-hr/{id}', [AdminController::class, 'approveHr'])->name('admin.approve.hr');

        // Admin login (separate guard)
        Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
        Route::post('/admin/login', [AdminAuthController::class, 'login']);
    });

    // ======================
    // HR ROUTES
    // ======================
    Route::middleware(['auth.hr'])->group(function () {
    // HR Dashboard
    Route::get('/hr/dashboard', [HrController::class, 'dashboard'])->name('hr.dashboard');

    // Employees
    Route::resource('employees', EmployeeController::class);
    Route::prefix('employees/{employee}')->group(function () {
        Route::resource('schedules', EmployeeScheduleController::class);
        Route::resource('payslips', EmployeePayslipController::class);
        Route::resource('deductions', EmployeeDeductionController::class);
    });
    Route::middleware(['auth.hr'])->group(function () {
        Route::prefix('employees/{employee}')->group(function () {
            Route::post('/schedules', [EmployeeScheduleController::class, 'store'])
                ->name('employees.schedules.store');
    
            Route::get('schedules/view/{file}', [EmployeeScheduleController::class, 'viewSchedule'])
                ->name('employees.schedules.view');
    
            Route::get('schedules/download/{id}', [EmployeeScheduleController::class, 'download'])
                ->name('employees.schedules.download');
        });
    });

    // Calendar
    Route::resource('calendar', CalendarController::class);
    Route::post('/calendar/reset', [CalendarController::class, 'reset'])->name('calendar.reset');

    // Schedule (company-wide, not per-employee)
    Route::resource('schedule', ScheduleController::class);
    Route::post('/schedule/save', [ScheduleController::class, 'save'])->name('schedule.save');
    });

    
    // Deduction routes
    Route::resource('deductions', DeductionSettingController::class)->only(['index', 'update']);
    // Late & Absences
    Route::post('deductions/late/save', [DeductionSettingController::class, 'saveLateAbsence'])
        ->name('deductions.late.save');
    // SSS Brackets
    Route::prefix('deductions/sss')->group(function () {
        // Save new bracket
        Route::post('/', [DeductionSettingController::class, 'storeSSS'])->name('deductions.sss.store');
        // Update existing bracket
        Route::put('/{id}', [DeductionSettingController::class, 'updateSSS'])->name('deductions.sss.update');
        // Delete bracket
        Route::delete('/{id}', [DeductionSettingController::class, 'destroySSS'])->name('deductions.sss.destroy');
    });


    



    

    



