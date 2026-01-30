<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AAFile extends Model
{
    /** @use HasFactory<\Database\Factories\AAFileFactory> */
    use HasFactory;

    public function aa_items() {
        return $this->hasMany(AAItem::class);
    }
    public function aa_batch()
    {
        return $this->belongsTo(AABatch::class);
    }
}
