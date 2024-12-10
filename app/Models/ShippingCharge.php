<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingCharge extends Model
{
    use HasFactory;
    protected $fillable = ['province_id', 'shipping_cost'];

    public function province()
    {
        return $this->belongsTo(Province::class);
    }
}
