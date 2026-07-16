<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\BillRequest;
use App\Models\Bill;
use App\Models\Student;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;

class BillController extends AdminResourceController
{
    protected string $modelClass = Bill::class;

    protected string $routeName = 'admin.bills';

    protected string $baseUrl = '/admin/bills';

    protected string $title = 'Tagihan';

    protected string $singularTitle = 'Tagihan';

    protected array $columns = [
        ['key' => 'title', 'label' => 'Tagihan'],
        ['key' => 'student_name', 'label' => 'Siswa'],
        ['key' => 'amount', 'label' => 'Nominal', 'type' => 'money'],
        ['key' => 'paid_amount', 'label' => 'Terbayar', 'type' => 'money'],
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

    public function store(BillRequest $request): RedirectResponse
    {
        return $this->storeResource($request);
    }

    public function edit(Bill $bill): Response
    {
        return $this->editResource($bill);
    }

    public function update(BillRequest $request, Bill $bill): RedirectResponse
    {
        return $this->updateResource($request, $bill);
    }

    public function destroy(Bill $bill): RedirectResponse
    {
        return $this->destroyResource($bill);
    }

    protected function fields(): array
    {
        return [
            ['name' => 'student_id', 'label' => 'Siswa', 'type' => 'select', 'options' => $this->studentOptions()],
            ['name' => 'nipd', 'label' => 'NIPD'],
            ['name' => 'nisn', 'label' => 'NISN'],
            ['name' => 'title', 'label' => 'Nama tagihan', 'required' => true],
            ['name' => 'description', 'label' => 'Keterangan', 'type' => 'textarea', 'colSpan' => 2],
            ['name' => 'amount', 'label' => 'Nominal', 'type' => 'number', 'required' => true],
            ['name' => 'paid_amount', 'label' => 'Terbayar', 'type' => 'number'],
            ['name' => 'due_date', 'label' => 'Jatuh tempo', 'type' => 'date'],
            ['name' => 'status', 'label' => 'Status', 'type' => 'select', 'required' => true, 'options' => $this->statusOptions(['pending', 'partial', 'paid', 'cancelled'])],
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
            $data['nisn'] = $data['nisn'] ?: $student->nisn;
        }

        if (($data['status'] ?? null) === 'paid') {
            $data['paid_amount'] = $data['paid_amount'] ?: $data['amount'];
            $data['paid_at'] = $record instanceof Bill && $record->paid_at ? $record->paid_at : now();
        }

        return $data;
    }

    protected function serializeRecord(Model $record): array
    {
        $data = parent::serializeRecord($record);
        $data['student_name'] = $record instanceof Bill ? $record->student?->name : null;

        return $data;
    }
}
