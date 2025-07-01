<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 
        'description', 
        'price', 
        'stock_quantity', 
        'is_active', 
        'category_id',
        'cost_price',
        'image'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'is_active' => 'boolean',
        'stock_quantity' => 'integer'
    ];

    protected $attributes = [
        'is_active' => true,
        'stock_quantity' => 0
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($product) {
            if ($product->is_active) {
                $product->is_active = false;
                $product->save();
            }
        });

        static::restoring(function ($product) {
            $product->is_active = true;
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class)->withDefault([
            'name' => 'Uncategorized',
            'id' => null
        ]);
    }

    public function getFormattedPriceAttribute()
    {
        return '$' . number_format($this->price, 2);
    }

    public function getFormattedCostPriceAttribute()
    {
        return $this->cost_price ? '$' . number_format($this->cost_price, 2) : 'N/A';
    }

    public function getStockStatusAttribute()
    {
        if ($this->stock_quantity <= 0) {
            return 'Out of Stock';
        }
        return $this->stock_quantity < 10 ? 'Low Stock' : 'In Stock';
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    public function scopeLowStock($query, $threshold = 10)
    {
        return $query->where('stock_quantity', '<', $threshold);
    }
}