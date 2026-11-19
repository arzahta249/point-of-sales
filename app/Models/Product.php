<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $table = 'products';
    public $timestamps = false;
    protected $fillable = ['category_id', 'sku', 'nama', 'harga_beli', 'harga_jual', 'stok', 'stok_minimum'];

    protected $casts = [
        'harga_beli'   => 'decimal:2',
        'harga_jual'   => 'decimal:2',
        'stok'         => 'integer',
        'stok_minimum' => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function transactionItems(): HasMany
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function stockLogs(): HasMany
    {
        return $this->hasMany(StockLog::class);
    }
}

