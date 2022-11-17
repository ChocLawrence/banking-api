<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Account extends Model
{
    //
    use HasFactory;
    
    protected $table = 'accounts';
    protected $primary_key = 'id';

    protected $fillable = ['account_number', 'balance', 'user_id', 'account_type'];


    /**
     * Account belongs to a user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Transfer belongs to an account.(can be made to or from it)
     */
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

}