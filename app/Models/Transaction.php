<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'sender_account',
        'receiver_account',
        'amount',
        'status',
        'currency'
    ];

    protected $hidden = [
        'user_id'
    ];


    /**
     * Get the user that owns the Transaction. Effector in essence: customer, employee or admin
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the all accounts affected by a Transaction.
     */
    public function accounts(): HasMany
    {
        return $this->hasMany(Account::class);
    }

}