<?php

namespace App\Observers;

use App\Jobs\ComputeBillCost;
use App\Models\Bill;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class BillObserver
{
    public function creating(Bill $bill)
    {
        //
    }

    public function updating(Bill $bill)
    {
        //
    }

    public function saved(Bill $bill)
    {

    }

    public function updated(Bill $bill)
    {
//        // 计算对应bill的人均付费情况。
//        dispatch(new ComputeBillCost($bill));
    }


}