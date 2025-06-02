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
        'product_id',
        'supplier_id',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    protected static function booted()
    {
        static::created(function ($log) {
            $log->product->increment('stock', $log->quantity);
        });

        static::updated(function ($log) {
            $originalQty = $log->getOriginal('quantity');
            $originalProduct = $log->getOriginal('product_id');

            if ($log->product_id != $originalProduct) {
                // Jika ganti produk
                \App\Models\Product::find($originalProduct)?->decrement('stock', $originalQty);
                $log->product->increment('stock', $log->quantity);
            } else {
                // Jika tetap produk yang sama, tapi jumlah berubah
                $diff = $log->quantity - $originalQty;
                $log->product->increment('stock', $diff);
            }
        });

        static::deleted(function ($log) {
            $log->product->decrement('stock', $log->quantity);
        });
    }
}
