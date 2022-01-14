<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pin extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'user_id',
        'account_id',
        'pin',
    ];

    protected $hidden = [
        'user_id'
    ];


    /**
     * Get the account that has the pin
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }
}
