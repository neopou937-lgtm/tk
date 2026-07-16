<?php

namespace App\Http\Requests\Admin;

use App\Models\Teacher;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TeacherRequest extends FormRequest
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
        $teacher = $this->route('teacher');
        $teacherId = $teacher instanceof Teacher ? $teacher->id : null;

        return [
            'name' => ['required', 'string', 'max:255'],
            'employee_number' => ['nullable', 'string', 'max:50', Rule::unique('teachers', 'employee_number')->ignore($teacherId)],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'subject' => ['nullable', 'string', 'max:100'],
            'class_name' => ['nullable', 'string', 'max:50'],
            'photo_file' => ['nullable', 'image', 'max:2048'],
            'status' => ['required', Rule::in(['aktif', 'nonaktif'])],
        ];
    }
}
