<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['source_firestore_id', 'title', 'body', 'audience', 'type', 'payload', 'read_at'])]
class AdminNotification extends Model
{
    protected $table = 'notifications';

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'payload' => 'array',
            'read_at' => 'datetime',
        ];
    }
}
