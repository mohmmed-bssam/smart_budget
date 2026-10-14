<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model {
    //

    protected $guarded = [];
    protected $casts = [
        'name' => 'array',
        'balance' => 'decimal:2',
    ];

    public function user() {
        return $this->belongsTo( User::class );
    }

    public function transactions() {
        return $this->hasMany( Transaction::class );
    }

    public function savings() {
        return $this->hasMany( Saving::class );
    }

    public function outgoingTransfers() {
        return $this->hasMany(
            Transfer::class,
            'from_account_id'
        );
    }

    public function incomingTransfers() {
        return $this->hasMany(
            Transfer::class,
            'to_account_id'
        );
    }
}
