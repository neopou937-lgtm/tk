<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'source_firestore_id',
    'student_id',
    'name',
    'nipd',
    'nisn',
    'class_name',
    'graduation_year',
    'graduated_at',
    'total_bills',
    'total_savings',
    'snapshot',
])]
class Alumni extends Model
{
    protected $table = 'alumni';

    /**
     * @return BelongsTo<Student, $this>
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'graduated_at' => 'date',
            'total_bills' => 'decimal:2',
            'total_savings' => 'decimal:2',
            'snapshot' => 'array',
        ];
    }
}
