<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillGroup extends Model
{
    //

    protected $fillable = ['group_name'];

    public function billGroupUsers()
    {
        return $this->hasMany(BillGroupUser::class, 'bill_group_id', 'id');
    }
}
