<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    protected $table = 'customers';
    public $timestamps = false;
    protected $fillable = ['nama', 'no_hp'];

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}

