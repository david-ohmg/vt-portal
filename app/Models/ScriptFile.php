<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScriptFile extends Model
{
    /** @use HasFactory<\Database\Factories\ScriptFileFactory> */
    use HasFactory;

    public function script_batch() {
        return $this->belongsTo(ScriptBatch::class);
    }

    public function script_items() {
        return $this->hasMany(ScriptItem::class);
    }
}
