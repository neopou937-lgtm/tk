<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['source_firestore_id', 'teacher_id', 'journal_date', 'class_name', 'topic', 'notes'])]
class TeachingJournal extends Model
{
    /**
     * @return BelongsTo<Teacher, $this>
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'journal_date' => 'date',
        ];
    }
}
