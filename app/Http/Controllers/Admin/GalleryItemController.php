<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\GalleryItemRequest;
use App\Models\GalleryItem;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;

class GalleryItemController extends AdminResourceController
{
    protected string $modelClass = GalleryItem::class;

    protected string $baseUrl = '/admin/gallery';

    protected string $routeName = 'admin.gallery';

    protected string $title = 'Galeri';

    protected string $singularTitle = 'Galeri';

    protected array $columns = [
        ['key' => 'title', 'label' => 'Judul'],
        ['key' => 'media_type', 'label' => 'Media', 'type' => 'badge'],
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

    public function store(GalleryItemRequest $request): RedirectResponse
    {
        return $this->storeResource($request);
    }

    public function edit(GalleryItem $gallery): Response
    {
        return $this->editResource($gallery);
    }

    public function update(GalleryItemRequest $request, GalleryItem $gallery): RedirectResponse
    {
        return $this->updateResource($request, $gallery);
    }

    public function destroy(GalleryItem $gallery): RedirectResponse
    {
        return $this->destroyResource($gallery);
    }

    protected function fields(): array
    {
        return [
            ['name' => 'title', 'label' => 'Judul', 'required' => true],
            ['name' => 'media_type', 'label' => 'Tipe media', 'type' => 'select', 'required' => true, 'options' => $this->statusOptions(['image', 'video'])],
            ['name' => 'media_file', 'label' => 'Upload media', 'type' => 'file', 'pathColumn' => 'media_path', 'urlColumn' => 'media_url', 'directory' => 'gallery'],
            ['name' => 'media_url', 'label' => 'URL media lama/Cloudinary'],
            ['name' => 'description', 'label' => 'Deskripsi', 'type' => 'textarea', 'colSpan' => 2],
            ['name' => 'status', 'label' => 'Status', 'type' => 'select', 'required' => true, 'options' => $this->statusOptions(['draft', 'published'])],
        ];
    }
}
