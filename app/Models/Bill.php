<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    const BILL_NEEDS_COMPUTE = 1;
    const BILL_APPENDING = 2;
    const BILL_DONE = 0;
    //
    protected $fillable = [ 'path', 'tips', 'payment_user_id', 'total_bill', 'bill_needs_average',
        'bill_group_id', 'is_needs_compute', 'slug'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function paymentUser()
    {
        return $this->belongsTo(User::class, 'payment_user_id', 'id');
    }

    public function billUsers()
    {
        return $this->hasMany(BillUser::class);

    }

    public function billDetails()
    {
        return $this->hasMany(BillDetail::class);
    }

    public function scopeWithOrder($query, $order)
    {
        // 不同的排序，使用不同的数据读取逻辑
        switch ($order) {
            case 'recent':
                $query->recent();
                break;

            default:
                $query->recentReplied();
                break;
        }
    }
    public function scopeRecentReplied($query)
    {
        // 当话题有新回复时，我们将编写逻辑来更新话题模型的 reply_count 属性，
        // 此时会自动触发框架对数据模型 updated_at 时间戳的更新
        return $query->orderBy('updated_at', 'desc');
    }

    public function link($params = [])
    {
        return route('bills.show', array_merge([$this->id, $this->slug], $params));
    }




}
