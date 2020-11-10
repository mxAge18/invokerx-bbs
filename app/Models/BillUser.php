<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillUser extends Model
{
    //
    protected $fillable = ['user_id', 'bill_id', 'tips', 'cost'];

    public function bill()
    {
        return $this->belongsTo(Bill::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
