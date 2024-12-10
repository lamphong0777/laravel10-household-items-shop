<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoodsReceipt extends Model
{
    use HasFactory;
    protected $fillable = ['id', 'producer_id', 'staff_id', 'import_date', 'total_price', 'notes'];

    public function receiptDetails()
    {
        return $this->hasMany(ReceiptDetail::class, 'good_receipt_id');
    }

    public function producer()
    {
        return $this->belongsTo(Producer::class, 'producer_id');
    }
    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }
}
