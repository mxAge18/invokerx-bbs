<?php

namespace App\Jobs;

use App\Models\Bill;
use App\Models\BillUser;
use App\Models\BillDetail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ComputeBillCost implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $bill;
    protected $bills;

    protected $total;
    protected $bill_user_number;
    protected $single_needs_pay;
    protected $total_needs_average;

    protected $bill_group_user;
    protected $bill_users;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Bill $bill)
    {
        //
        $this->bill = $bill;
        $this->bill_users = \DB::table('bill_users')->where('bill_id', $this->bill->id)->get();
        $this->bill_group_user = \DB::table('bill_group_users')
            ->where('bill_group_id', $this->bill->bill_group_id)
            ->get();


    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        if ($this->bill->is_needs_compute == Bill::BILL_NEEDS_COMPUTE) {
            $this->computeAverage();
        }
        $this->computeUserBill();

    }

    protected function computeAverage()
    {
        $this->total = $this->bill->total_bill;
        $this->single_needs_pay = \DB::table('bill_details')
            ->where('bill_id', $this->bill->id)
            ->sum('needs_single_pay');

        $this->bill_user_number = \DB::table('bill_group_users')->where('bill_group_id', $this->bill->bill_group_id)
            ->sum('coefficient');
        $this->total_needs_average = ($this->total - $this->single_needs_pay) / $this->bill_user_number;
        $billData = \DB::table('bill_details')->where('bill_id', $this->bill->id)->get();
        \DB::table('bills')->where('id', $this->bill->id)
            ->update(['bill_needs_average' => $this->total_needs_average,
                'is_needs_compute' => 0]);

        foreach ($this->bill_users as $datum) {
            $coefficient = 1;
            foreach ($this->bill_group_user as $billGroupUserDatum) {
                if ($billGroupUserDatum->user_id == $datum->user_id) {
                    $coefficient = $billGroupUserDatum->coefficient;
                    break;
                }
            }
            foreach ($billData as $billDatum) {
                if ($billDatum->user_id == $datum->user_id) {
                    \DB::table('bill_users')
                        ->where([
                            ['user_id', $datum->user_id],
                            ['bill_id', $this->bill->id]
                        ])->update(['cost' => $this->total_needs_average * $coefficient + $billDatum->needs_single_pay]);
                    break;
                }
            }
        }
    }


    protected function computeUserBill()
    {

        foreach ($this->bill_group_user as $item) {
            $total = \DB::table('bills')
                ->where([['payment_user_id', $item->user_id],['bill_group_id', $this->bill->bill_group_id]])
                ->sum('total_bill');
            $cost = \DB::table('bill_users')
                ->where([['bill_users.user_id', $item->user_id],
                        ['bills.bill_group_id', $this->bill->bill_group_id]
                    ])->leftJoin('bills', 'bill_users.bill_id','=', 'bills.id')
                ->sum('bill_users.cost');

            \DB::table('bill_group_users')
                ->where([['bill_group_id', $this->bill->bill_group_id], ['user_id', $item->user_id]])
                ->update(['user_payment_number' => $total,
                    'user_cost_number' => $cost,
                    'user_overdraft'   => $total - $cost
                    ]);
        }

    }

}
