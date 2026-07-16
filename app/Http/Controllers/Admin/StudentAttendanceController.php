<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\StudentAttendanceRequest;
use App\Models\Student;
use App\Models\StudentAttendance;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;

class StudentAttendanceController extends AdminResourceController
{
    protected string $modelClass = StudentAttendance::class;

    protected string $routeName = 'admin.student-attendances';

    protected string $baseUrl = '/admin/student-attendance';

    protected string $title = 'Absensi Siswa';

    protected string $singularTitle = 'Absensi Siswa';

    protected array $columns = [
        ['key' => 'student_name', 'label' => 'Siswa'],
        ['key' => 'attendance_date', 'label' => 'Tanggal'],
        ['key' => 'status', 'label' => 'Status', 'type' => 'badge'],
        ['key' => 'note', 'label' => 'Catatan'],
    ];

    public function index(): Response
    {
        return $this->indexResource();
    }

    public function create(): Response
    {
        return $this->createResource();
    }

    public function store(StudentAttendanceRequest $request): RedirectResponse
    {
        return $this->storeResource($request);
    }

    public function edit(StudentAttendance $studentAttendance): Response
    {
        return $this->editResource($studentAttendance);
    }

    public function update(StudentAttendanceRequest $request, StudentAttendance $studentAttendance): RedirectResponse
    {
        return $this->updateResource($request, $studentAttendance);
    }

    public function destroy(StudentAttendance $studentAttendance): RedirectResponse
    {
        return $this->destroyResource($studentAttendance);
    }

    protected function fields(): array
    {
        return [
            ['name' => 'student_id', 'label' => 'Siswa', 'type' => 'select', 'options' => $this->studentOptions()],
            ['name' => 'nipd', 'label' => 'NIPD'],
            ['name' => 'attendance_date', 'label' => 'Tanggal', 'type' => 'date', 'required' => true],
            ['name' => 'status', 'label' => 'Status', 'type' => 'select', 'required' => true, 'options' => $this->statusOptions(['hadir', 'sakit', 'izin', 'alpa'])],
            ['name' => 'note', 'label' => 'Catatan', 'type' => 'textarea', 'colSpan' => 2],
        ];
    }

    protected function prepareData(array $data, ?Model $record): array
    {
        $data = parent::prepareData($data, $record);
        $student = filled($data['student_id'] ?? null)
            ? Student::query()->whereKey($data['student_id'])->first()
            : null;

        if ($student) {
            $data['nipd'] = $data['nipd'] ?: $student->nipd;
        }

        return $data;
    }

    protected function serializeRecord(Model $record): array
    {
        $data = parent::serializeRecord($record);
        $data['student_name'] = $record instanceof StudentAttendance ? $record->student?->name : null;

        return $data;
    }
}
