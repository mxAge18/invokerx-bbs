<?php

namespace App\Observers;

use App\Jobs\ComputeBillCost;
use App\Models\Bill;
use \App\Models\BillDetail;

class BillDetailsObserver
{
    /**
     * Handle the bill detail "created" event.
     *
     * @param  \App\Models\BillDetail  $billDetail
     * @return void
     */
    public function created(BillDetail $billDetail)
    {
        //
    }

    /**
     * Handle the bill detail "updated" event.
     *
     * @param  \App\Models\BillDetail  $billDetail
     * @return void
     */
    public function updated(BillDetail $billDetail)
    {

    }

    /**
     * Handle the bill detail "deleted" event.
     *
     * @param  \App\Models\BillDetail  $billDetail
     * @return void
     */
    public function deleted(BillDetail $billDetail)
    {
        //
    }

    /**
     * Handle the bill detail "restored" event.
     *
     * @param \App\Models\BillDetail  $billDetail
     * @return void
     */
    public function restored(BillDetail $billDetail)
    {
        //
    }

    /**
     * Handle the bill detail "force deleted" event.
     *
     * @param  \App\Models\BillDetail  $billDetail
     * @return void
     */
    public function forceDeleted(BillDetail $billDetail)
    {
        //
    }
}
