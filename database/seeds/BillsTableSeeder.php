<?php

use Illuminate\Database\Seeder;
use App\Models\Bill;

class BillsTableSeeder extends Seeder
{
    public function run()
    {
        $bills = factory(Bill::class)->times(50)->make()->each(function ($bill, $index) {
            if ($index == 0) {
                // $bill->field = 'value';
            }
        });

        Bill::insert($bills->toArray());
    }

}

