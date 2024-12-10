<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceiptDetail extends Model
{
    use HasFactory;
    protected $fillable = ['id', 'product_id', 'good_receipt_id', 'import_qty', 'remaining_qty', 'import_price'];

    public function goodsReceipt()
    {
        return $this->belongsTo(GoodsReceipt::class, 'good_receipt_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
