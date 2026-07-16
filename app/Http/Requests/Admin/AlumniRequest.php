<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AlumniRequest extends FormRequest
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
            'student_id' => ['nullable', 'exists:students,id'],
            'name' => ['required', 'string', 'max:255'],
            'nipd' => ['nullable', 'string', 'max:50'],
            'nisn' => ['nullable', 'string', 'max:50'],
            'class_name' => ['nullable', 'string', 'max:50'],
            'graduation_year' => ['nullable', 'integer', 'min:2000', 'max:2100'],
            'graduated_at' => ['nullable', 'date'],
            'total_bills' => ['nullable', 'numeric', 'min:0'],
            'total_savings' => ['nullable', 'numeric'],
        ];
    }
}
