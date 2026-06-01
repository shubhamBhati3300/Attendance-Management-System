<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\StudentController;
use App\Http\Controllers\AttendanceController;

Route::get('/', function () {
    return view('welcome');
});

// Student management + enrollment
Route::get('/students', [StudentController::class, 'index'])->name('students.index');
Route::get('/students/create', [StudentController::class, 'create'])->name('students.create');
Route::post('/students', [StudentController::class, 'store'])->name('students.store');
Route::get('/students/{id}/enroll', [StudentController::class, 'enroll'])->name('students.enroll');
Route::post('/students/{id}/embedding', [StudentController::class, 'storeEmbedding'])->name('students.embedding');
Route::get('/api/students/labels', [StudentController::class, 'labels'])->name('students.labels');

// Attendance
Route::get('/attendance/scan', [AttendanceController::class, 'scan'])->name('attendance.scan');
Route::post('/attendance/mark', [AttendanceController::class, 'mark'])->name('attendance.mark');
