<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\TeacherRequest;
use App\Models\Teacher;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;

class TeacherController extends AdminResourceController
{
    protected string $modelClass = Teacher::class;

    protected string $routeName = 'admin.teachers';

    protected string $baseUrl = '/admin/teachers';

    protected string $title = 'Data Guru';

    protected string $singularTitle = 'Guru';

    protected array $columns = [
        ['key' => 'name', 'label' => 'Nama'],
        ['key' => 'employee_number', 'label' => 'NIP/ID'],
        ['key' => 'phone', 'label' => 'No HP'],
        ['key' => 'class_name', 'label' => 'Kelas'],
        ['key' => 'status', 'label' => 'Status', 'type' => 'badge'],
    ];

    public function index(): Response
    {
        return $this->indexResource();
    }

    public function create(): Response
    {
        return $this->createResource();
    }

    public function store(TeacherRequest $request): RedirectResponse
    {
        return $this->storeResource($request);
    }

    public function edit(Teacher $teacher): Response
    {
        return $this->editResource($teacher);
    }

    public function update(TeacherRequest $request, Teacher $teacher): RedirectResponse
    {
        return $this->updateResource($request, $teacher);
    }

    public function destroy(Teacher $teacher): RedirectResponse
    {
        return $this->destroyResource($teacher);
    }

    protected function fields(): array
    {
        return [
            ['name' => 'name', 'label' => 'Nama guru', 'required' => true],
            ['name' => 'employee_number', 'label' => 'NIP/ID guru'],
            ['name' => 'email', 'label' => 'Email', 'type' => 'email'],
            ['name' => 'phone', 'label' => 'No HP'],
            ['name' => 'subject', 'label' => 'Mapel/Tugas'],
            ['name' => 'class_name', 'label' => 'Kelas wali'],
            ['name' => 'photo_file', 'label' => 'Foto guru', 'type' => 'file', 'pathColumn' => 'photo_path', 'urlColumn' => 'photo_url', 'directory' => 'teachers/photos'],
            ['name' => 'status', 'label' => 'Status', 'type' => 'select', 'required' => true, 'options' => $this->statusOptions(['aktif', 'nonaktif'])],
        ];
    }
}
