<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_date',
        'total'
    ];
    public $timestamps = true;

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)
            ->using(TransactionDetail::class)
            ->withPivot('quantity')
            ->withPivot('price')
            ->withPivot('sub_total')
            ->withTimestamps();
    }
}
