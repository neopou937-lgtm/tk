<?php

namespace App\Http\Requests\Admin;

use App\Models\PpdbApplication;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PpdbApplicationRequest extends FormRequest
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
        $ppdb = $this->route('ppdb_application');
        $ppdbId = $ppdb instanceof PpdbApplication ? $ppdb->id : null;

        return [
            'registration_number' => ['nullable', 'string', 'max:80', Rule::unique('ppdb_applications', 'registration_number')->ignore($ppdbId)],
            'student_id' => ['nullable', 'exists:students,id'],
            'child_name' => ['required', 'string', 'max:255'],
            'nipd' => ['nullable', 'string', 'max:50'],
            'level' => ['nullable', 'string', 'max:50'],
            'class_name' => ['nullable', 'string', 'max:50'],
            'gender' => ['nullable', 'string', 'max:50'],
            'birth_date' => ['nullable', 'date'],
            'address' => ['nullable', 'string', 'max:1000'],
            'parent_name' => ['nullable', 'string', 'max:255'],
            'parent_phone' => ['nullable', 'string', 'max:50'],
            'status' => ['required', Rule::in(['pending', 'diterima', 'ditolak'])],
            'submitted_at' => ['nullable', 'date'],
        ];
    }
}
