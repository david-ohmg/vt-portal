<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MyFiles extends Model
{
    protected $fillable = ['name', 'path', 'type', 'size', 'mime_type', 'extension', 'description', 'user_id'];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
