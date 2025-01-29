<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/admin/login');
});


Route::get('employees/methods', [App\Http\Controllers\EmployeeController::class, 'methods']);

Route::get('attendances/methods', [App\Http\Controllers\AttendanceController::class, 'methods']);

Route::get('leave-requests/methods', [App\Http\Controllers\LeaveRequestController::class, 'methods']);

Route::get('payslips/methods', [App\Http\Controllers\PayslipController::class, 'methods']);


Route::get('employees/methods', [App\Http\Controllers\EmployeeController::class, 'methods']);

Route::get('attendances/methods', [App\Http\Controllers\AttendanceController::class, 'methods']);

Route::get('leave-requests/methods', [App\Http\Controllers\LeaveRequestController::class, 'methods']);

Route::get('payslips/methods', [App\Http\Controllers\PayslipController::class, 'methods']);
