<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class TransactionDetail extends Pivot
{
    use HasFactory;

    protected $table = 'transaction_details';

    protected $fillable = ['transaction_id', 'product_id', 'quantity', 'price', 'sub_total'];
    public $timestamps = true;

    protected static function booted()
    {
        // Saat create, kurangi stok produk
        static::created(function ($detail) {
            $product = $detail->product;
            if ($product) {
                $product->stock -= $detail->quantity;
                $product->save();
            }
        });

        // Saat update, sesuaikan stok berdasarkan selisih quantity lama dan baru
        static::updating(function ($detail) {
            $originalQuantity = $detail->getOriginal('quantity');
            $newQuantity = $detail->quantity;

            $difference = $newQuantity - $originalQuantity;

            $product = $detail->product;
            if ($product) {
                $product->stock -= $difference;
                $product->save();
            }
        });

        // Saat delete, tambahkan kembali stok produk
        static::deleted(function ($detail) {
            $product = $detail->product;
            if ($product) {
                $product->stock += $detail->quantity;
                $product->save();
            }
        });
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }
}
