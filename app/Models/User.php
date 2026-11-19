<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    protected $table = 'users';
    public $timestamps = false;
    protected $fillable = ['nama', 'username', 'password_hash', 'role'];
    protected $hidden = ['password_hash'];

    // Override kolom password untuk Laravel Auth
    public function getAuthPassword(): string
    {
        return $this->password_hash;
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function stockLogs(): HasMany
    {
        return $this->hasMany(StockLog::class);
    }
}
