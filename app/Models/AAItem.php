<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AAItem extends Model
{
    /** @use HasFactory<\Database\Factories\AAItemFactory> */
    use HasFactory;

    public function aa_file() {
        return $this->belongsTo(AAFile::class);
    }

    public function aa_batch() {
        return $this->belongsTo(AABatch::class);
    }
}
