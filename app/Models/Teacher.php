<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'source_firestore_id',
    'name',
    'employee_number',
    'email',
    'phone',
    'subject',
    'class_name',
    'photo_path',
    'photo_url',
    'status',
])]
class Teacher extends Model
{
    /**
     * @return HasMany<TeacherAttendance, $this>
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(TeacherAttendance::class);
    }
}
