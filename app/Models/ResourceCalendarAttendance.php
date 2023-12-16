<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResourceCalendarAttendance extends Model
{
    use HasFactory;
    protected $table = 'resource_calendar_attendances';
    protected $fillable = [
        'calendar_id',
        'sequence',
        'name',
        'dayofweek',
        'day_period',
        'week_type',
        'display_type',
        'date_from',
        'date_to',
        'hour_from',
        'hour_to',
    ];
    public function calendar()
    {
        return $this->belongsTo(ResourceCalendar::class, 'calendar_id');
    }
}
