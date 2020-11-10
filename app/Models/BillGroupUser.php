<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cache;

class BillGroupUser extends Model
{
    //
    protected $fillable = [];


    public function billGroup()
    {
        return $this->belongsTo(BillGroup::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function getAllCached()
    {
        return Cache::remember($this->cache_key, $this->cache_expire_in_seconds, function(){
            return $this->all();
        });
    }
}
