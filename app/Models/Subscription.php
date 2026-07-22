<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    //
    protected $fillable = [
         'user_id',
         'name', 
         'description', 
         'price', 
         'currency', 
         'interval', 
         'interval_count', 
         'trial_period_days', 
         'status'
        ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
