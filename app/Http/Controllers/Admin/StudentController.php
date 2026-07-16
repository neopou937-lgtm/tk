<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreStudentRequest;
use App\Http\Requests\Admin\UpdateStudentRequest;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class StudentController extends Controller
{
    /**
     * Display the student list.
     */
    public function index(): Response
    {
        return Inertia::render('admin/students/index', [
            'students' => Student::query()
                ->latest()
                ->get([
                    'id',
                    'name',
                    'nipd',
                    'nisn',
                    'level',
                    'class_name',
                    'gender',
                    'status',
                    'created_at',
                ]),
        ]);
    }

    /**
     * Show the student creation page.
     */
    public function create(): Response
    {
        return Inertia::render('admin/students/form');
    }

    /**
     * Store a newly created student.
     */
    public function store(StoreStudentRequest $request): RedirectResponse
    {
        $student = Student::create($this->studentData($request->validated()));

        $this->storeUploads($student, $request);

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => 'Data siswa berhasil disimpan.',
        ]);

        return to_route('admin.students.index');
    }

    /**
     * Show the student edit page.
     */
    public function edit(Student $student): Response
    {
        return Inertia::render('admin/students/form', [
            'student' => [
                ...$student->toArray(),
                'student_photo_url' => $student->student_photo_url ?: $this->publicUrl($student->student_photo_path),
                'family_card_photo_url' => $student->family_card_photo_url ?: $this->publicUrl($student->family_card_photo_path),
                'parent_id_card_photo_url' => $student->parent_id_card_photo_url ?: $this->publicUrl($student->parent_id_card_photo_path),
            ],
        ]);
    }

    /**
     * Update the selected student.
     */
    public function update(UpdateStudentRequest $request, Student $student): RedirectResponse
    {
        $student->update($this->studentData($request->validated()));

        $this->storeUploads($student, $request);

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => 'Data siswa berhasil diperbarui.',
        ]);

        return to_route('admin.students.edit', $student);
    }

    /**
     * @param  array<string, mixed>  $validated
     * @return array<string, mixed>
     */
    private function studentData(array $validated): array
    {
        unset(
            $validated['student_photo'],
            $validated['family_card_photo'],
            $validated['parent_id_card_photo'],
        );

        return $validated;
    }

    private function storeUploads(Student $student, StoreStudentRequest|UpdateStudentRequest $request): void
    {
        $this->storeUpload($student, $request, 'student_photo', 'student_photo_path', 'students/photos');
        $this->storeUpload($student, $request, 'family_card_photo', 'family_card_photo_path', 'students/family-cards');
        $this->storeUpload($student, $request, 'parent_id_card_photo', 'parent_id_card_photo_path', 'students/parent-ids');
    }

    private function storeUpload(
        Student $student,
        StoreStudentRequest|UpdateStudentRequest $request,
        string $input,
        string $column,
        string $directory,
    ): void {
        if (! $request->hasFile($input)) {
            return;
        }

        $oldPath = $student->{$column};
        $path = $request->file($input)->store($directory, 'public');

        if (is_string($oldPath) && $oldPath !== '') {
            Storage::disk('public')->delete($oldPath);
        }

        $student->forceFill([$column => $path])->save();
    }

    private function publicUrl(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        return Storage::disk('public')->url($path);
    }
}
