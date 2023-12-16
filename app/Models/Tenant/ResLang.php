<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResLang extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'code',
        'iso_code',
        'url_code',
        'direction',
        'date_format',
        'time_format',
        'week_start',
        'grouping',
        'decimal_point',
        'thousands_sep',
        'active',
    ];
}
