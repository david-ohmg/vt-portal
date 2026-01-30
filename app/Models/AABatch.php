<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AABatch extends Model
{
    /** @use HasFactory<\Database\Factories\AABatchFactory> */
    use HasFactory;

    public function aa_files() {
        return $this->hasMany(AAFile::class);
    }

    public function aa_items() {
        return $this->hasMany(AAItem::class);
    }
}
