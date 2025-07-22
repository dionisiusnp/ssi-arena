<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CodeBlock extends Model
{
    protected $fillable = [
        'user_id',
        'code_content',
        'language',
        'description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
