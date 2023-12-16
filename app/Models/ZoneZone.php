<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZoneZone extends Model
{
    use HasFactory;
    protected $table = 'zone_zones';

    protected $fillable = [
        'warehouse_id',
        'company_id',
        'name',
        'marker_color',
        'get_map',
        'get_geo_lines',
        'get_drawing',
        'show_fee',
        'delivery_fees',
    ];


    public function company()
    {
        return $this->belongsTo(ResCompany::class);
    }

   
    public function lattitude_longitudes(){
        return $this->hasMany(LatitudeLongitude::class,'zone_id');
    }
    public function saleOrder(){
        return $this->hasMany(SaleOrder::class,'zone_id');
    }


    
    
}
