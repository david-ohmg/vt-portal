<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScriptBatch extends Model
{
    /** @use HasFactory<\Database\Factories\ScriptBatchFactory> */
    use HasFactory;

    public function script_files() {
        return $this->hasMany(ScriptFile::class);
    }

    public function script_items() {
        return $this->hasMany(ScriptItem::class);
    }
}
