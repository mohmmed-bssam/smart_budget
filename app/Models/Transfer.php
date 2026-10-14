<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transfer extends Model {
    //

    protected $guarded = [];
    protected $casts = [
        'from_amount' => 'decimal:2',
        'to_amount' => 'decimal:2',
        'exchange_rate' => 'decimal:8',
        'transfer_date' => 'date',
    ];

    public function user() {
        return $this->belongsTo( User::class );
    }

    public function fromAccount() {
        return $this->belongsTo(
            Account::class,
            'from_account_id'
        );
    }

    public function toAccount() {
        return $this->belongsTo(
            Account::class,
            'to_account_id'
        );
    }

}