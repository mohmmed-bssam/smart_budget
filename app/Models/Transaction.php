<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model {
    //

    protected $guarded = [];
    protected $casts = [
        'amount' => 'decimal:2',
        'account_amount' => 'decimal:2',
        'exchange_rate' => 'decimal:8',
        'base_amount' => 'decimal:2',
        'transaction_date' => 'date',
    ];

    public function user() {
        return $this->belongsTo( User::class );
    }

    public function account() {
        return $this->belongsTo( Account::class );
    }

    public function category() {
        return $this->belongsTo( Category::class );
    }
}