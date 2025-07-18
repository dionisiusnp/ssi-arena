<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Schedule extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;
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

    public function getStatusAttribute()
    {
        $now = Carbon::now();
        $start = Carbon::parse($this->attributes['started_at']);
        $end = Carbon::parse($this->attributes['finished_at']);

        if ($now->isBetween($start->startOfDay(), $end->endOfDay())) {
            return 'Berlangsung';
        } elseif ($now->lt($start)) {
            return 'Akan Datang';
        }

        return 'Selesai';
    }

    public function getBadgeClassAttribute()
    {
        switch ($this->status) {
            case 'Berlangsung':
                return 'bg-primary';
            case 'Akan Datang':
                return 'bg-warning';
            default:
                return 'bg-danger';
        }
    }

    public function lastChanger()
    {
        return $this->belongsTo(User::class,'changed_by');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('schedule_img')->useDisk('media');
    }
}
