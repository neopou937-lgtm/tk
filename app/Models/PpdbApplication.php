<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'source_firestore_id',
    'registration_number',
    'student_id',
    'child_name',
    'nipd',
    'level',
    'class_name',
    'gender',
    'birth_date',
    'address',
    'parent_name',
    'parent_phone',
    'status',
    'submitted_at',
])]
class PpdbApplication extends Model
{
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
            'birth_date' => 'date',
            'submitted_at' => 'datetime',
        ];
    }
}
