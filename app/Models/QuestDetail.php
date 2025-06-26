<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuestDetail extends Model
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

    public function season() {
        return $this->belongsTo(Season::class,'season_id');
    }

    public function questType() {
        return $this->belongsTo(QuestType::class,'quest_type_id');
    }

    public function questLevel() {
        return $this->belongsTo(QuestLevel::class,'quest_level_id');
    }

    public function requirements() {
        return $this->hasMany(QuestRequirement::class);
    }

    public function activities() {
        return $this->hasMany(Activity::class,'quest_detail_id');
    }

    public function getClaimableUsersAttribute()
    {
        if (empty($this->claimable_by)) {
            return collect();
        }
        $ids = is_array($this->claimable_by)
        ? $this->claimable_by
        : json_decode($this->claimable_by, true);
        return \App\Models\User::whereIn('id', $ids)->get();
    }

    public function getClaimableNamesAttribute()
    {
        $users = $this->claimable_users;
        return $users->isNotEmpty()
            ? $users->pluck('name')->implode(', ')
            : 'Semua Member';
    }
}
