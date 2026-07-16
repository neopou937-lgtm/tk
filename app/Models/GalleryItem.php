<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'source_firestore_id',
    'title',
    'description',
    'media_type',
    'media_path',
    'media_url',
    'status',
])]
class GalleryItem extends Model
{
    //
}
