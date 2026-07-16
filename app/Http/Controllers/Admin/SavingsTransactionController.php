<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\SavingsTransactionRequest;
use App\Models\SavingsTransaction;
use App\Models\Student;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;

class SavingsTransactionController extends AdminResourceController
{
    protected string $modelClass = SavingsTransaction::class;

    protected string $routeName = 'admin.savings-transactions';

    protected string $baseUrl = '/admin/savings';

    protected string $title = 'Tabungan';

    protected string $singularTitle = 'Transaksi Tabungan';

    protected array $columns = [
        ['key' => 'student_name', 'label' => 'Siswa'],
        ['key' => 'type', 'label' => 'Jenis', 'type' => 'badge'],
        ['key' => 'amount', 'label' => 'Nominal', 'type' => 'money'],
        ['key' => 'transaction_date', 'label' => 'Tanggal'],
    ];

    public function index(): Response
    {
        return $this->indexResource();
    }

    public function create(): Response
    {
        return $this->createResource();
    }

    public function store(SavingsTransactionRequest $request): RedirectResponse
    {
        return $this->storeResource($request);
    }

    public function edit(SavingsTransaction $saving): Response
    {
        return $this->editResource($saving);
    }

    public function update(SavingsTransactionRequest $request, SavingsTransaction $saving): RedirectResponse
    {
        return $this->updateResource($request, $saving);
    }

    public function destroy(SavingsTransaction $saving): RedirectResponse
    {
        return $this->destroyResource($saving);
    }

    protected function fields(): array
    {
        return [
            ['name' => 'student_id', 'label' => 'Siswa', 'type' => 'select', 'options' => $this->studentOptions()],
            ['name' => 'nipd', 'label' => 'NIPD'],
            ['name' => 'student_name', 'label' => 'Nama siswa'],
            ['name' => 'type', 'label' => 'Jenis transaksi', 'type' => 'select', 'required' => true, 'options' => $this->statusOptions(['setor', 'tarik'])],
            ['name' => 'amount', 'label' => 'Nominal', 'type' => 'number', 'required' => true],
            ['name' => 'transaction_date', 'label' => 'Tanggal', 'type' => 'date'],
            ['name' => 'description', 'label' => 'Keterangan', 'type' => 'textarea', 'colSpan' => 2],
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
            $data['student_name'] = $data['student_name'] ?: $student->name;
        }

        return $data;
    }
}
