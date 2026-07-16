<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

abstract class AdminResourceController extends Controller
{
    /** @var class-string<Model> */
    protected string $modelClass;

    protected string $routeName;

    protected string $baseUrl;

    protected string $title;

    protected string $singularTitle;

    /** @var list<array<string, mixed>> */
    protected array $columns = [];

    /** @var list<string> */
    protected array $searchColumns = [];

    public function indexResource(): Response
    {
        $records = $this->query()
            ->latest()
            ->get()
            ->map(fn (Model $record): array => $this->serializeRecord($record))
            ->values();

        return Inertia::render('admin/resources/index', [
            'title' => $this->title,
            'singularTitle' => $this->singularTitle,
            'baseUrl' => $this->baseUrl,
            'columns' => $this->columns,
            'records' => $records,
        ]);
    }

    public function createResource(): Response
    {
        return Inertia::render('admin/resources/form', [
            'title' => "Tambah {$this->singularTitle}",
            'singularTitle' => $this->singularTitle,
            'baseUrl' => $this->baseUrl,
            'fields' => $this->fields(),
            'record' => null,
        ]);
    }

    public function editResource(Model $record): Response
    {
        return Inertia::render('admin/resources/form', [
            'title' => "Edit {$this->singularTitle}",
            'singularTitle' => $this->singularTitle,
            'baseUrl' => $this->baseUrl,
            'fields' => $this->fields(),
            'record' => $this->serializeRecord($record),
        ]);
    }

    public function storeResource(FormRequest $request): RedirectResponse
    {
        $data = $this->prepareData($request->validated(), null);
        $record = new $this->modelClass;
        $record->fill($data);
        $this->storeFiles($request, $record);
        $record->save();
        $this->afterSave($record, $request);

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => "{$this->singularTitle} berhasil disimpan.",
        ]);

        return redirect($this->baseUrl);
    }

    public function updateResource(FormRequest $request, Model $record): RedirectResponse
    {
        $data = $this->prepareData($request->validated(), $record);
        $record->fill($data);
        $this->storeFiles($request, $record);
        $record->save();
        $this->afterSave($record, $request);

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => "{$this->singularTitle} berhasil diperbarui.",
        ]);

        return redirect("{$this->baseUrl}/{$record->getKey()}/edit");
    }

    public function destroyResource(Model $record): RedirectResponse
    {
        $record->delete();

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => "{$this->singularTitle} berhasil dihapus.",
        ]);

        return redirect($this->baseUrl);
    }

    /**
     * @return Builder<Model>
     */
    protected function query(): Builder
    {
        /** @var Builder<Model> $query */
        $query = $this->modelClass::query();

        return $query;
    }

    /**
     * @return list<array<string, mixed>>
     */
    abstract protected function fields(): array;

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function prepareData(array $data, ?Model $record): array
    {
        foreach ($this->fields() as $field) {
            if (($field['type'] ?? null) === 'file') {
                unset($data[$field['name']]);
            }
        }

        return $data;
    }

    protected function afterSave(Model $record, FormRequest $request): void
    {
        //
    }

    protected function storeFiles(FormRequest $request, Model $record): void
    {
        foreach ($this->fields() as $field) {
            if (($field['type'] ?? null) !== 'file') {
                continue;
            }

            $name = (string) $field['name'];
            if (! $request->hasFile($name)) {
                continue;
            }

            $file = $request->file($name);
            if (! $file) {
                continue;
            }

            $pathColumn = (string) ($field['pathColumn'] ?? $name);
            $directory = (string) ($field['directory'] ?? 'admin-uploads');
            $oldPath = $record->{$pathColumn};
            $path = $file->store($directory, 'public');

            if (is_string($oldPath) && $oldPath !== '') {
                Storage::disk('public')->delete($oldPath);
            }

            $record->{$pathColumn} = $path;
        }
    }

    /**
     * @return array<string, mixed>
     */
    protected function serializeRecord(Model $record): array
    {
        $data = $record->toArray();
        $data['id'] = $record->getKey();

        foreach ($this->fields() as $field) {
            if (($field['type'] ?? null) !== 'file') {
                continue;
            }

            $pathColumn = (string) ($field['pathColumn'] ?? $field['name']);
            $urlColumn = (string) ($field['urlColumn'] ?? "{$pathColumn}_url");
            $path = $record->{$pathColumn};
            $legacyUrl = $record->{$urlColumn};

            $data[$urlColumn] = is_string($legacyUrl) && $legacyUrl !== ''
                ? $legacyUrl
                : (is_string($path) && $path !== '' ? Storage::disk('public')->url($path) : null);
        }

        return $data;
    }

    /**
     * @param  list<string>  $values
     * @return list<array{label: string, value: string}>
     */
    protected function statusOptions(array $values): array
    {
        $options = [];

        foreach ($values as $value) {
            $options[] = [
                'label' => str($value)->replace('-', ' ')->title()->toString(),
                'value' => $value,
            ];
        }

        return $options;
    }

    /**
     * @return list<array{label: string, value: string}>
     */
    protected function studentOptions(): array
    {
        $options = [];

        foreach (Student::query()->orderBy('name')->get(['id', 'name', 'nipd']) as $student) {
            $options[] = [
                'label' => "{$student->name} ({$student->nipd})",
                'value' => (string) $student->id,
            ];
        }

        return $options;
    }

    /**
     * @return list<array{label: string, value: string}>
     */
    protected function teacherOptions(): array
    {
        $options = [];

        foreach (Teacher::query()->orderBy('name')->get(['id', 'name']) as $teacher) {
            $options[] = [
                'label' => $teacher->name,
                'value' => (string) $teacher->id,
            ];
        }

        return $options;
    }
}
