<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Saving extends Model
{
    //

    protected $guarded=[];
    protected $casts = [
        'amount' => 'decimal:2',
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