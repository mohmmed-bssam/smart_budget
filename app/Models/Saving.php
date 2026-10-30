<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Saving extends Model
{
    //

    protected $guarded=[];
   protected $casts = [
        'amount' => 'decimal:2',
        'source_amount' => 'decimal:2',
        'exchange_rate' => 'decimal:8',
        'date' => 'date',
    ];


    public function goal()
    {
        return $this->belongsTo(Goal::class);
    }


    public function account()
    {
        return $this->belongsTo(Account::class);
    }
    }