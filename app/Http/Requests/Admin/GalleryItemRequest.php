<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GalleryItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, list<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'media_type' => ['required', Rule::in(['image', 'video'])],
            'media_file' => ['nullable', 'file', 'max:10240'],
            'media_url' => ['nullable', 'url', 'max:1000'],
            'status' => ['required', Rule::in(['draft', 'published'])],
        ];
    }
}
