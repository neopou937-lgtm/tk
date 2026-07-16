<?php

use App\Http\Controllers\Admin\AlumniController;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\BillController;
use App\Http\Controllers\Admin\BillPaymentController;
use App\Http\Controllers\Admin\BopTransactionController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DonorController;
use App\Http\Controllers\Admin\GalleryItemController;
use App\Http\Controllers\Admin\PpdbApplicationController;
use App\Http\Controllers\Admin\SavingsTransactionController;
use App\Http\Controllers\Admin\StudentAttendanceController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\TeacherAttendanceController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\TeachingJournalController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('dashboard', DashboardController::class)->name('dashboard');

        Route::resource('students', StudentController::class)
            ->only(['index', 'create', 'store', 'edit', 'update']);
        Route::post('students/{student}/graduate', [AlumniController::class, 'graduate'])
            ->name('students.graduate');

        Route::resource('teachers', TeacherController::class)->except(['show']);
        Route::resource('ppdb', PpdbApplicationController::class)
            ->parameters(['ppdb' => 'ppdbApplication'])
            ->except(['show']);
        Route::resource('bills', BillController::class)->except(['show']);
        Route::post('bills/{bill}/midtrans', [BillPaymentController::class, 'createMidtransTransaction'])
            ->name('bills.midtrans');
        Route::post('bills/{bill}/mark-paid', [BillPaymentController::class, 'markPaid'])
            ->name('bills.mark-paid');

        Route::resource('savings', SavingsTransactionController::class)
            ->parameters(['savings' => 'saving'])
            ->except(['show']);
        Route::resource('bop-finance', BopTransactionController::class)
            ->parameters(['bop-finance' => 'bopFinance'])
            ->except(['show']);
        Route::resource('student-attendance', StudentAttendanceController::class)
            ->parameters(['student-attendance' => 'studentAttendance'])
            ->except(['show']);
        Route::resource('teacher-attendance', TeacherAttendanceController::class)
            ->parameters(['teacher-attendance' => 'teacherAttendance'])
            ->except(['show']);
        Route::resource('announcements', AnnouncementController::class)->except(['show']);
        Route::resource('gallery', GalleryItemController::class)
            ->parameters(['gallery' => 'gallery'])
            ->except(['show']);
        Route::resource('alumni', AlumniController::class)
            ->parameters(['alumni' => 'alumnus'])
            ->except(['show']);
        Route::resource('teaching-journals', TeachingJournalController::class)
            ->parameters(['teaching-journals' => 'teachingJournal'])
            ->except(['show']);
        Route::resource('donors', DonorController::class)->except(['show']);
    });
