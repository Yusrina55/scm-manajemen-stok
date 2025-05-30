<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'price'
    ];
    public $timestamps = true;

    public function transactionDetails(): HasMany
    {
        return $this->hasMany(TransactionDetail::class);
    }
    public function receivingLogs(): HasMany
    {
        return $this->hasMany(ReceivingLog::class);
    }
}
