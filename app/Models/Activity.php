<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Activity extends Model
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

    public function lastChanger()
    {
        return $this->belongsTo(User::class,'changed_by');
    }

    public function claimedBy()
    {
        return $this->belongsTo(User::class,'claimed_by');
    }

    public function detail() {
        return $this->belongsTo(QuestDetail::class,'quest_detail_id');
    }

    public function checklists() {
        return $this->hasMany(ActivityChecklist::class);
    }
}
