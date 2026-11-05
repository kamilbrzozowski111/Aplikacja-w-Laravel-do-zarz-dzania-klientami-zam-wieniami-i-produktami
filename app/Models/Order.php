<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;

    protected $fillable = [
        'order_number',
        'client_id',
        'status',
        'total_price',
        'order_date',
        'notes',
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
        'order_date' => 'date',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class)
            ->withPivot('quantity', 'unit_price')
            ->withTimestamps();
    }

    public function recalculateTotalPrice(): void
    {
        $total = $this->products()->get()->sum(function ($product) {
            return $product->pivot->quantity * $product->pivot->unit_price;
        });

        $this->update(['total_price' => $total]);
    }
}
