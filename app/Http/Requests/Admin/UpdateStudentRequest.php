<?php

namespace App\Http\Requests\Admin;

use App\Models\Student;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStudentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, list<mixed>|string>
     */
    public function rules(): array
    {
        $student = $this->route('student');
        $studentId = $student instanceof Student ? $student->id : null;

        return [
            'name' => ['required', 'string', 'max:255'],
            'nipd' => ['required', 'string', 'max:50', Rule::unique('students', 'nipd')->ignore($studentId)],
            'nisn' => ['nullable', 'string', 'max:50', Rule::unique('students', 'nisn')->ignore($studentId)],
            'student_nik' => ['nullable', 'string', 'max:50', Rule::unique('students', 'student_nik')->ignore($studentId)],
            'level' => ['required', 'string', 'max:50'],
            'class_name' => ['required', 'string', 'max:50'],
            'gender' => ['required', Rule::in(['laki-laki', 'perempuan'])],
            'birth_place' => ['nullable', 'string', 'max:100'],
            'birth_date' => ['nullable', 'date'],
            'religion' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:1000'],
            'child_order' => ['nullable', 'integer', 'min:1', 'max:20'],
            'father_name' => ['nullable', 'string', 'max:255'],
            'father_nik' => ['nullable', 'string', 'max:50'],
            'father_phone' => ['nullable', 'string', 'max:30'],
            'mother_name' => ['nullable', 'string', 'max:255'],
            'mother_nik' => ['nullable', 'string', 'max:50'],
            'mother_phone' => ['nullable', 'string', 'max:30'],
            'student_photo' => ['nullable', 'image', 'max:2048'],
            'family_card_photo' => ['nullable', 'image', 'max:2048'],
            'parent_id_card_photo' => ['nullable', 'image', 'max:2048'],
            'status' => ['required', Rule::in(['aktif', 'lulus', 'nonaktif'])],
        ];
    }
}
