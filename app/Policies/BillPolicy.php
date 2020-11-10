<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Bill;

class BillPolicy extends Policy
{
    public function update(User $user, Bill $bill)
    {
        // return $bill->user_id == $user->id;
        return true;
    }

    public function destroy(User $user, Bill $bill)
    {
        return true;
    }
}
