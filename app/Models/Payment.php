<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $table = 'payments';
    public $timestamps = false;
    protected $fillable = ['transaction_id', 'metode', 'jumlah_bayar', 'kembalian'];

    protected $casts = [
        'jumlah_bayar' => 'decimal:2',
        'kembalian'    => 'decimal:2',
    ];

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }
}

