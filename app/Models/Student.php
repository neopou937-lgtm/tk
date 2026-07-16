<?php

namespace App\Models;

use Database\Factories\StudentFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property string $nipd
 * @property string|null $nisn
 * @property string|null $student_nik
 * @property string $level
 * @property string $class_name
 * @property string $gender
 * @property string|null $birth_place
 * @property Carbon|null $birth_date
 * @property string|null $religion
 * @property string|null $address
 * @property int|null $child_order
 * @property string|null $father_name
 * @property string|null $father_nik
 * @property string|null $father_phone
 * @property string|null $mother_name
 * @property string|null $mother_nik
 * @property string|null $mother_phone
 * @property string|null $student_photo_path
 * @property string|null $family_card_photo_path
 * @property string|null $parent_id_card_photo_path
 * @property string $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable([
    'name',
    'nipd',
    'nisn',
    'student_nik',
    'level',
    'class_name',
    'gender',
    'birth_place',
    'birth_date',
    'religion',
    'address',
    'child_order',
    'father_name',
    'father_nik',
    'father_phone',
    'mother_name',
    'mother_nik',
    'mother_phone',
    'student_photo_path',
    'family_card_photo_path',
    'parent_id_card_photo_path',
    'status',
])]
class Student extends Model
{
    /** @use HasFactory<StudentFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'child_order' => 'integer',
        ];
    }
}
