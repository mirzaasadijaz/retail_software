<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'name',
        'quantity',
        'price',
        'net_total',
        'sku',
        'description',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'net_total' => 'decimal:2',
    ];

    protected static function booted()
    {
        static::saving(function ($product) {
            $product->net_total = $product->quantity * $product->price;
        });
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(InventoryTransaction::class);
    }

    public function updateInventory(string $type, int $quantity): void
    {
        if ($type === 'add') {
            $this->quantity += $quantity;
        } elseif ($type === 'use') {
            $this->quantity -= $quantity;
        }

        $this->save();
    }
}
