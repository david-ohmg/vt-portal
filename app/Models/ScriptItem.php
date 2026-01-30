<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScriptItem extends Model
{
    /** @use HasFactory<\Database\Factories\ScriptItemFactory> */
    use HasFactory;

    public function script_file() {
        return $this->belongsTo(ScriptFile::class);
    }

    public function script_batch() {
        return $this->belongsTo(ScriptBatch::class);
    }
}
