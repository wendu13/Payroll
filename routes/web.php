<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
// use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\HrController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EmployeeScheduleController;
use App\Http\Controllers\DeductionSettingController;
use App\Http\Controllers\SSSController;
use App\Http\Controllers\TaxController;

// AUTH ROUTES
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// HR Registration
Route::get('/register', [AuthController::class, 'showHrRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.store');

// HR Forgot Password
Route::get('/forgot-password', [AuthController::class, 'showForgot']);
Route::post('/forgot-password', [AuthController::class, 'resetPassword']);

// HR ROUTES
Route::middleware(['auth.hr'])->group(function () {
    // HR Dashboard
    Route::get('/hr/dashboard', [HrController::class, 'dashboard'])->name('hr.dashboard');

    // Employees
    Route::resource('employees', EmployeeController::class);
    Route::prefix('employees/{employee}')->group(function () {
        Route::resource('schedules', EmployeeScheduleController::class);
        // Route::resource('payslips', EmployeePayslipController::class); // Comment out
        // Route::resource('deductions', EmployeeDeductionController::class); // Comment out
    });
    
    Route::prefix('employees/{employee}')->group(function () {
        Route::post('/schedules', [EmployeeScheduleController::class, 'store'])
            ->name('employees.schedules.store');

        Route::get('schedules/view/{file}', [EmployeeScheduleController::class, 'viewSchedule'])
            ->name('employees.schedules.view');

        Route::get('schedules/download/{id}', [EmployeeScheduleController::class, 'download'])
            ->name('employees.schedules.download');
    });

    // Calendar
    Route::resource('calendar', CalendarController::class);
    Route::post('/calendar/reset', [CalendarController::class, 'reset'])->name('calendar.reset');

    // Schedule (company-wide, not per-employee)
    Route::resource('schedule', ScheduleController::class);
    Route::post('/schedule/save', [ScheduleController::class, 'save'])->name('schedule.save');
    
    // Deduction routes
    Route::resource('deductions', DeductionSettingController::class)->only(['index', 'update']);
    
    // Late & Absences
    Route::post('deductions/late/save', [DeductionSettingController::class, 'saveLateAbsence'])
        ->name('deductions.late.save');

    // SSS Routes - separate controller
    Route::prefix('sss')->name('sss.')->group(function () {
        Route::get('/', [SSSController::class, 'index'])->name('index');
        Route::post('/', [SSSController::class, 'store'])->name('store');
        Route::put('/', [SSSController::class, 'update'])->name('update');
        Route::delete('/{id}', [SSSController::class, 'destroy'])->name('destroy');
    });
    
    // HDMF Contribution
    Route::put('/deductions/hdmf', [DeductionSettingController::class, 'updateHdmf'])->name('deductions.hdmf.update');

    // Tax Routes
    Route::prefix('tax')->name('tax.')->group(function () {
        Route::get('/', [TaxController::class, 'index'])->name('index');
        Route::post('/', [TaxController::class, 'store'])->name('store');
        Route::put('/', [TaxController::class, 'update'])->name('update');
        Route::delete('/{id}', [TaxController::class, 'destroy'])->name('destroy');
    });
});