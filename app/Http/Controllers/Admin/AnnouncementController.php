<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\AnnouncementRequest;
use App\Models\Announcement;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;

class AnnouncementController extends AdminResourceController
{
    protected string $modelClass = Announcement::class;

    protected string $baseUrl = '/admin/announcements';

    protected string $routeName = 'admin.announcements';

    protected string $title = 'Pengumuman';

    protected string $singularTitle = 'Pengumuman';

    protected array $columns = [
        ['key' => 'title', 'label' => 'Judul'],
        ['key' => 'audience', 'label' => 'Tujuan', 'type' => 'badge'],
        ['key' => 'status', 'label' => 'Status', 'type' => 'badge'],
        ['key' => 'published_at', 'label' => 'Terbit'],
    ];

    public function index(): Response
    {
        return $this->indexResource();
    }

    public function create(): Response
    {
        return $this->createResource();
    }

    public function store(AnnouncementRequest $request): RedirectResponse
    {
        return $this->storeResource($request);
    }

    public function edit(Announcement $announcement): Response
    {
        return $this->editResource($announcement);
    }

    public function update(AnnouncementRequest $request, Announcement $announcement): RedirectResponse
    {
        return $this->updateResource($request, $announcement);
    }

    public function destroy(Announcement $announcement): RedirectResponse
    {
        return $this->destroyResource($announcement);
    }

    protected function fields(): array
    {
        return [
            ['name' => 'title', 'label' => 'Judul', 'required' => true],
            ['name' => 'body', 'label' => 'Isi pengumuman', 'type' => 'textarea', 'required' => true, 'colSpan' => 2],
            ['name' => 'audience', 'label' => 'Tujuan', 'type' => 'select', 'required' => true, 'options' => $this->statusOptions(['all', 'admin', 'teacher', 'parent'])],
            ['name' => 'status', 'label' => 'Status', 'type' => 'select', 'required' => true, 'options' => $this->statusOptions(['draft', 'published'])],
            ['name' => 'published_at', 'label' => 'Waktu terbit', 'type' => 'datetime-local'],
        ];
    }
}
