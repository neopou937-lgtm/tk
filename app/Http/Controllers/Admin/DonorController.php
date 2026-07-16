<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\DonorRequest;
use App\Models\Donor;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;

class DonorController extends AdminResourceController
{
    protected string $modelClass = Donor::class;

    protected string $baseUrl = '/admin/donors';

    protected string $routeName = 'admin.donors';

    protected string $title = 'Donatur';

    protected string $singularTitle = 'Donatur';

    protected array $columns = [
        ['key' => 'name', 'label' => 'Nama'],
        ['key' => 'phone', 'label' => 'No HP'],
        ['key' => 'amount', 'label' => 'Nominal', 'type' => 'money'],
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

    public function store(DonorRequest $request): RedirectResponse
    {
        return $this->storeResource($request);
    }

    public function edit(Donor $donor): Response
    {
        return $this->editResource($donor);
    }

    public function update(DonorRequest $request, Donor $donor): RedirectResponse
    {
        return $this->updateResource($request, $donor);
    }

    public function destroy(Donor $donor): RedirectResponse
    {
        return $this->destroyResource($donor);
    }

    protected function fields(): array
    {
        return [
            ['name' => 'name', 'label' => 'Nama donatur', 'required' => true],
            ['name' => 'phone', 'label' => 'No HP'],
            ['name' => 'amount', 'label' => 'Nominal', 'type' => 'number'],
            ['name' => 'status', 'label' => 'Status', 'type' => 'select', 'required' => true, 'options' => $this->statusOptions(['pending', 'verified', 'rejected'])],
            ['name' => 'donated_at', 'label' => 'Tanggal donasi', 'type' => 'datetime-local'],
            ['name' => 'note', 'label' => 'Catatan', 'type' => 'textarea', 'colSpan' => 2],
        ];
    }
}
