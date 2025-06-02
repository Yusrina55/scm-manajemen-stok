<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_date',
        'total'
    ];
    public $timestamps = true;

    public function transactionDetails(): HasMany
    {
        return $this->hasMany(TransactionDetail::class);
    }

    protected static function booted(): void
    {
        static::created(function ($transaction) {
            foreach ($transaction->transactionDetails as $detail) {
                $product = $detail->product;
                $product->stock -= $detail->quantity;
                $product->save();
            }
        });

        static::deleting(function ($transaction) {
            foreach ($transaction->transactionDetails as $detail) {
                $detail->delete(); // akan otomatis trigger event 'deleted' di TransactionDetail
            }
        });
    }
    
}
