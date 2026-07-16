<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StudentAttendanceRequest extends FormRequest
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
            'nipd' => ['nullable', 'string', 'max:50'],
            'attendance_date' => ['required', 'date'],
            'status' => ['required', Rule::in(['hadir', 'sakit', 'izin', 'alpa'])],
            'note' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
