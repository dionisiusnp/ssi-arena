<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Season extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];
    public function getCreatedAtFormattedAttribute(): string
    {
        return Carbon::parse($this->attributes['created_at'])
            ->locale('id')
            ->translatedFormat("d F Y H:i");
    }

    public function getUpdatedAtFormattedAttribute(): string
    {
        return Carbon::parse($this->attributes['updated_at'])
            ->locale('id')
            ->translatedFormat("d F Y H:i");
    }

    public function getStartedAtFormattedAttribute(): string
    {
        return Carbon::parse($this->attributes['started_at'])
            ->locale('id')
            ->translatedFormat("d F Y");
    }

    public function getFinishedAtFormattedAttribute(): string
    {
        return Carbon::parse($this->attributes['finished_at'])
            ->locale('id')
            ->translatedFormat("d F Y");
    }

    public function lastChanger()
    {
        return $this->belongsTo(User::class,'changed_by');
    }
}
