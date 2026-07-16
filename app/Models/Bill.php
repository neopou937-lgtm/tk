<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'source_firestore_id',
    'student_id',
    'alumni_id',
    'nipd',
    'nisn',
    'title',
    'description',
    'amount',
    'paid_amount',
    'due_date',
    'status',
    'payment_status',
    'payment_gateway',
    'midtrans_order_id',
    'snap_token',
    'snap_redirect_url',
    'paid_at',
])]
class Bill extends Model
{
    /**
     * @return BelongsTo<Student, $this>
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * @return BelongsTo<Alumni, $this>
     */
    public function alumni(): BelongsTo
    {
        return $this->belongsTo(Alumni::class);
    }

    /**
     * @return HasMany<BillPayment, $this>
     */
    public function payments(): HasMany
    {
        return $this->hasMany(BillPayment::class);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'paid_amount' => 'decimal:2',
            'due_date' => 'date',
            'paid_at' => 'datetime',
        ];
    }
}
