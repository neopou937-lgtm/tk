<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SavingsTransactionRequest extends FormRequest
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
            'student_name' => ['nullable', 'string', 'max:255'],
            'type' => ['required', Rule::in(['setor', 'tarik'])],
            'amount' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string', 'max:1000'],
            'transaction_date' => ['nullable', 'date'],
        ];
    }
}
