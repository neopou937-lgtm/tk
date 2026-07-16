<?php

use App\Http\Controllers\Admin\StudentController;
use App\Models\Student;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware(['auth', 'verified'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('dashboard', function () {
            return Inertia::render('admin/dashboard', [
                'stats' => [
                    'students' => Student::count(),
                    'teachers' => 0,
                    'pendingBills' => 0,
                    'paidPayments' => 0,
                    'savings' => 0,
                    'bopFunds' => 0,
                    'pendingPpdb' => 0,
                    'alumni' => Student::where('status', 'lulus')->count(),
                ],
            ]);
        })->name('dashboard');

        Route::resource('students', StudentController::class)
            ->only(['index', 'create', 'store', 'edit', 'update']);

        $placeholders = [
            'teachers' => 'Data Guru',
            'ppdb' => 'PPDB',
            'bills' => 'Tagihan',
            'savings' => 'Tabungan',
            'bop-finance' => 'Keuangan BOP',
            'student-attendance' => 'Absensi Siswa',
            'teacher-attendance' => 'Absensi Guru',
            'announcements' => 'Pengumuman',
            'gallery' => 'Galeri',
            'alumni' => 'Alumni',
        ];

        foreach ($placeholders as $uri => $title) {
            Route::get($uri, fn () => Inertia::render('admin/placeholder', [
                'title' => $title,
            ]))->name($uri);
        }
    });
