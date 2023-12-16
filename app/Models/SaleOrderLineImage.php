<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleOrderLineImage extends Model
{
    use HasFactory;
    protected $fillable = ['image','order_line_id'];
    public function saleOrderLine(){
    return $this->belongsTo(SaleOrderLine::class,'order_line_id');
    }
}
