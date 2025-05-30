<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReceivingLog extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $fillable = [
        'quantity',
        'supplier_price',
        'entry_date',
        'product_id'
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
