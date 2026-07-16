<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\TeacherAttendanceRequest;
use App\Models\TeacherAttendance;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;

class TeacherAttendanceController extends AdminResourceController
{
    protected string $modelClass = TeacherAttendance::class;

    protected string $routeName = 'admin.teacher-attendances';

    protected string $baseUrl = '/admin/teacher-attendance';

    protected string $title = 'Absensi Guru';

    protected string $singularTitle = 'Absensi Guru';

    protected array $columns = [
        ['key' => 'teacher_name', 'label' => 'Guru'],
        ['key' => 'attendance_date', 'label' => 'Tanggal'],
        ['key' => 'status', 'label' => 'Status', 'type' => 'badge'],
        ['key' => 'check_in_at', 'label' => 'Masuk'],
        ['key' => 'check_out_at', 'label' => 'Pulang'],
    ];

    public function index(): Response
    {
        return $this->indexResource();
    }

    public function create(): Response
    {
        return $this->createResource();
    }

    public function store(TeacherAttendanceRequest $request): RedirectResponse
    {
        return $this->storeResource($request);
    }

    public function edit(TeacherAttendance $teacherAttendance): Response
    {
        return $this->editResource($teacherAttendance);
    }

    public function update(TeacherAttendanceRequest $request, TeacherAttendance $teacherAttendance): RedirectResponse
    {
        return $this->updateResource($request, $teacherAttendance);
    }

    public function destroy(TeacherAttendance $teacherAttendance): RedirectResponse
    {
        return $this->destroyResource($teacherAttendance);
    }

    protected function fields(): array
    {
        return [
            ['name' => 'teacher_id', 'label' => 'Guru', 'type' => 'select', 'options' => $this->teacherOptions()],
            ['name' => 'attendance_date', 'label' => 'Tanggal', 'type' => 'date', 'required' => true],
            ['name' => 'status', 'label' => 'Status', 'type' => 'select', 'required' => true, 'options' => $this->statusOptions(['hadir', 'sakit', 'izin', 'alpa'])],
            ['name' => 'check_in_at', 'label' => 'Jam masuk', 'type' => 'time'],
            ['name' => 'check_out_at', 'label' => 'Jam pulang', 'type' => 'time'],
            ['name' => 'note', 'label' => 'Catatan', 'type' => 'textarea', 'colSpan' => 2],
        ];
    }

    protected function serializeRecord(Model $record): array
    {
        $data = parent::serializeRecord($record);
        $data['teacher_name'] = $record instanceof TeacherAttendance ? $record->teacher?->name : null;

        return $data;
    }
}
