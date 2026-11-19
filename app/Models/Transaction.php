<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Transaction extends Model
{
    protected $table = 'transactions';
    public $timestamps = false;
    protected $fillable = [
        'user_id', 'customer_id', 'no_invoice',
        'tanggal', 'subtotal', 'diskon', 'total', 'status',
    ];

    protected $casts = [
        'tanggal'  => 'datetime',
        'subtotal' => 'decimal:2',
        'diskon'   => 'decimal:2',
        'total'    => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }
}

