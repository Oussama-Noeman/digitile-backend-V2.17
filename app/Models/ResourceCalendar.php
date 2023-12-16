<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResourceCalendar extends Model
{
    use HasFactory;
    protected $table = 'resource_calendars';

    protected $fillable = [
        'company_id',
        'name',
        'tz',
        'active',
        'two_weeks_calendar',
        'hours_per_day',
        'is_working_day',
    ];
    public function company()
    {
        return $this->belongsTo(ResCompany::class, 'company_id');
    }
    public function calendarAttendances()
    {
        return $this->hasMany(ResourceCalendarAttendance::class, 'calendar_id');
    }
}
