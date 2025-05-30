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

    public function transactions(): BelongsToMany
    {
        return $this->belongsToMany(Transaction::class)
            ->using(TransactionDetail::class)  // pakai model pivot khusus
            ->withPivot('quantity')
            ->withPivot('price')
            ->withPivot('sub_total')
            ->withTimestamps();
    }
    public function receivingLogs(): HasMany
    {
        return $this->hasMany(ReceivingLog::class);
    }
}
