<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transactions';
    protected $primary_key = 'id';


    protected $fillable = [
        'user_id',
        'sender_account_number',
        'receiver_account_number',
        'sender_account',
        'receiver_account',
        'amount',
        'status',
        'currency'
    ];

    /**
     * Get the user that owns the Transaction. Effector in essence: customer, employee or admin
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}