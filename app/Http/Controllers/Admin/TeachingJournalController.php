<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\TeachingJournalRequest;
use App\Models\TeachingJournal;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;

class TeachingJournalController extends AdminResourceController
{
    protected string $modelClass = TeachingJournal::class;

    protected string $baseUrl = '/admin/teaching-journals';

    protected string $routeName = 'admin.teaching-journals';

    protected string $title = 'Jurnal Mengajar';

    protected string $singularTitle = 'Jurnal Mengajar';

    protected array $columns = [
        ['key' => 'teacher_name', 'label' => 'Guru'],
        ['key' => 'journal_date', 'label' => 'Tanggal'],
        ['key' => 'class_name', 'label' => 'Kelas'],
        ['key' => 'topic', 'label' => 'Topik'],
    ];

    public function index(): Response
    {
        return $this->indexResource();
    }

    public function create(): Response
    {
        return $this->createResource();
    }

    public function store(TeachingJournalRequest $request): RedirectResponse
    {
        return $this->storeResource($request);
    }

    public function edit(TeachingJournal $teachingJournal): Response
    {
        return $this->editResource($teachingJournal);
    }

    public function update(TeachingJournalRequest $request, TeachingJournal $teachingJournal): RedirectResponse
    {
        return $this->updateResource($request, $teachingJournal);
    }

    public function destroy(TeachingJournal $teachingJournal): RedirectResponse
    {
        return $this->destroyResource($teachingJournal);
    }

    protected function fields(): array
    {
        return [
            ['name' => 'teacher_id', 'label' => 'Guru', 'type' => 'select', 'options' => $this->teacherOptions()],
            ['name' => 'journal_date', 'label' => 'Tanggal', 'type' => 'date'],
            ['name' => 'class_name', 'label' => 'Kelas'],
            ['name' => 'topic', 'label' => 'Topik', 'required' => true],
            ['name' => 'notes', 'label' => 'Catatan', 'type' => 'textarea', 'colSpan' => 2],
        ];
    }

    protected function serializeRecord(Model $record): array
    {
        $data = parent::serializeRecord($record);
        $data['teacher_name'] = $record instanceof TeachingJournal ? $record->teacher?->name : null;

        return $data;
    }
}
