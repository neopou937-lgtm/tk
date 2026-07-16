<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\PpdbApplicationRequest;
use App\Models\PpdbApplication;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;

class PpdbApplicationController extends AdminResourceController
{
    protected string $modelClass = PpdbApplication::class;

    protected string $routeName = 'admin.ppdb-applications';

    protected string $baseUrl = '/admin/ppdb';

    protected string $title = 'PPDB';

    protected string $singularTitle = 'Data PPDB';

    protected array $columns = [
        ['key' => 'child_name', 'label' => 'Nama calon siswa'],
        ['key' => 'registration_number', 'label' => 'No Daftar'],
        ['key' => 'level', 'label' => 'Jenjang'],
        ['key' => 'parent_phone', 'label' => 'No HP'],
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

    public function store(PpdbApplicationRequest $request): RedirectResponse
    {
        return $this->storeResource($request);
    }

    public function edit(PpdbApplication $ppdbApplication): Response
    {
        return $this->editResource($ppdbApplication);
    }

    public function update(PpdbApplicationRequest $request, PpdbApplication $ppdbApplication): RedirectResponse
    {
        return $this->updateResource($request, $ppdbApplication);
    }

    public function destroy(PpdbApplication $ppdbApplication): RedirectResponse
    {
        return $this->destroyResource($ppdbApplication);
    }

    protected function fields(): array
    {
        return [
            ['name' => 'registration_number', 'label' => 'No pendaftaran'],
            ['name' => 'child_name', 'label' => 'Nama calon siswa', 'required' => true],
            ['name' => 'nipd', 'label' => 'NIPD'],
            ['name' => 'level', 'label' => 'Jenjang'],
            ['name' => 'class_name', 'label' => 'Kelas'],
            ['name' => 'gender', 'label' => 'Jenis kelamin', 'type' => 'select', 'options' => $this->statusOptions(['laki-laki', 'perempuan'])],
            ['name' => 'birth_date', 'label' => 'Tanggal lahir', 'type' => 'date'],
            ['name' => 'address', 'label' => 'Alamat', 'type' => 'textarea', 'colSpan' => 2],
            ['name' => 'parent_name', 'label' => 'Nama orang tua'],
            ['name' => 'parent_phone', 'label' => 'No HP orang tua'],
            ['name' => 'status', 'label' => 'Status', 'type' => 'select', 'required' => true, 'options' => $this->statusOptions(['pending', 'diterima', 'ditolak'])],
            ['name' => 'submitted_at', 'label' => 'Tanggal daftar', 'type' => 'datetime-local'],
        ];
    }
}
