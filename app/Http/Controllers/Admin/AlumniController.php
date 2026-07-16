<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\AlumniRequest;
use App\Models\Alumni;
use App\Models\Bill;
use App\Models\SavingsTransaction;
use App\Models\Student;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class AlumniController extends AdminResourceController
{
    protected string $modelClass = Alumni::class;

    protected string $baseUrl = '/admin/alumni';

    protected string $routeName = 'admin.alumni';

    protected string $title = 'Alumni';

    protected string $singularTitle = 'Alumni';

    protected array $columns = [
        ['key' => 'name', 'label' => 'Nama'],
        ['key' => 'nipd', 'label' => 'NIPD'],
        ['key' => 'class_name', 'label' => 'Kelas'],
        ['key' => 'graduation_year', 'label' => 'Tahun lulus'],
        ['key' => 'total_savings', 'label' => 'Saldo tabungan', 'type' => 'money'],
    ];

    public function index(): Response
    {
        return $this->indexResource();
    }

    public function create(): Response
    {
        return $this->createResource();
    }

    public function store(AlumniRequest $request): RedirectResponse
    {
        return $this->storeResource($request);
    }

    public function edit(Alumni $alumnus): Response
    {
        return $this->editResource($alumnus);
    }

    public function update(AlumniRequest $request, Alumni $alumnus): RedirectResponse
    {
        return $this->updateResource($request, $alumnus);
    }

    public function destroy(Alumni $alumnus): RedirectResponse
    {
        return $this->destroyResource($alumnus);
    }

    public function graduate(Student $student): RedirectResponse
    {
        DB::transaction(function () use ($student): void {
            $billTotal = Bill::where('student_id', $student->id)->sum('amount');
            $savingSetor = SavingsTransaction::where('student_id', $student->id)
                ->where('type', 'setor')
                ->sum('amount');
            $savingTarik = SavingsTransaction::where('student_id', $student->id)
                ->where('type', 'tarik')
                ->sum('amount');

            $alumni = Alumni::updateOrCreate(
                ['student_id' => $student->id],
                [
                    'name' => $student->name,
                    'nipd' => $student->nipd,
                    'nisn' => $student->nisn,
                    'class_name' => $student->class_name,
                    'graduation_year' => now()->year,
                    'graduated_at' => now()->toDateString(),
                    'total_bills' => $billTotal,
                    'total_savings' => $savingSetor - $savingTarik,
                    'snapshot' => $student->toArray(),
                ],
            );

            Bill::where('student_id', $student->id)->update(['alumni_id' => $alumni->id]);
            SavingsTransaction::where('student_id', $student->id)->update(['alumni_id' => $alumni->id]);
            $student->update(['status' => 'lulus']);
        });

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => "{$student->name} berhasil dipindahkan ke alumni.",
        ]);

        return redirect('/admin/students');
    }

    protected function fields(): array
    {
        return [
            ['name' => 'student_id', 'label' => 'Siswa asal', 'type' => 'select', 'options' => $this->studentOptions()],
            ['name' => 'name', 'label' => 'Nama alumni', 'required' => true],
            ['name' => 'nipd', 'label' => 'NIPD'],
            ['name' => 'nisn', 'label' => 'NISN'],
            ['name' => 'class_name', 'label' => 'Kelas terakhir'],
            ['name' => 'graduation_year', 'label' => 'Tahun lulus', 'type' => 'number'],
            ['name' => 'graduated_at', 'label' => 'Tanggal lulus', 'type' => 'date'],
            ['name' => 'total_bills', 'label' => 'Total tagihan', 'type' => 'number'],
            ['name' => 'total_savings', 'label' => 'Saldo tabungan', 'type' => 'number'],
        ];
    }

    protected function prepareData(array $data, ?Model $record): array
    {
        $data = parent::prepareData($data, $record);
        $student = filled($data['student_id'] ?? null)
            ? Student::query()->whereKey($data['student_id'])->first()
            : null;

        if ($student) {
            $data['name'] = $data['name'] ?: $student->name;
            $data['nipd'] = $data['nipd'] ?: $student->nipd;
            $data['nisn'] = $data['nisn'] ?: $student->nisn;
            $data['class_name'] = $data['class_name'] ?: $student->class_name;
        }

        return $data;
    }
}
