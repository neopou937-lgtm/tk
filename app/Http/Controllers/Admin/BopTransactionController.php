<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\BopTransactionRequest;
use App\Models\BopTransaction;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;

class BopTransactionController extends AdminResourceController
{
    protected string $modelClass = BopTransaction::class;

    protected string $routeName = 'admin.bop-transactions';

    protected string $baseUrl = '/admin/bop-finance';

    protected string $title = 'Keuangan BOP';

    protected string $singularTitle = 'Transaksi BOP';

    protected array $columns = [
        ['key' => 'transaction_date', 'label' => 'Tanggal'],
        ['key' => 'type', 'label' => 'Jenis', 'type' => 'badge'],
        ['key' => 'amount', 'label' => 'Nominal', 'type' => 'money'],
        ['key' => 'description', 'label' => 'Keterangan'],
    ];

    public function index(): Response
    {
        return $this->indexResource();
    }

    public function create(): Response
    {
        return $this->createResource();
    }

    public function store(BopTransactionRequest $request): RedirectResponse
    {
        return $this->storeResource($request);
    }

    public function edit(BopTransaction $bopFinance): Response
    {
        return $this->editResource($bopFinance);
    }

    public function update(BopTransactionRequest $request, BopTransaction $bopFinance): RedirectResponse
    {
        return $this->updateResource($request, $bopFinance);
    }

    public function destroy(BopTransaction $bopFinance): RedirectResponse
    {
        return $this->destroyResource($bopFinance);
    }

    protected function fields(): array
    {
        return [
            ['name' => 'type', 'label' => 'Jenis', 'type' => 'select', 'required' => true, 'options' => $this->statusOptions(['debit', 'kredit'])],
            ['name' => 'amount', 'label' => 'Nominal', 'type' => 'number', 'required' => true],
            ['name' => 'transaction_date', 'label' => 'Tanggal', 'type' => 'date'],
            ['name' => 'description', 'label' => 'Keterangan', 'type' => 'textarea', 'colSpan' => 2],
        ];
    }
}
