<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class AccountType extends Model
{
    use HasFactory;

    protected $table = 'accounttypes';
    protected $primary_key = 'id';

    protected $fillable = [
        'name',
        'minimum_amount',
        'maximum_amount',
    ];


    /**
     * Get the account that has  account type
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }


}