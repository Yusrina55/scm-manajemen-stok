<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class TransactionDetail extends Pivot
{
    use HasFactory;

    protected $fillable = ['transaction_id', 'product_id', 'quantity', 'price', 'sub_total'];

}
