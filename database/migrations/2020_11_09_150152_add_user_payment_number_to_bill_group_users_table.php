<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserPaymentNumberToBillGroupUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bill_group_users', function (Blueprint $table) {
            //
            $table->double('user_payment_number')->default(0);
            $table->double('user_cost_number')->default(0);
            $table->double('user_overdraft')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bill_group_users', function (Blueprint $table) {
            //
            $table->dropColumn('user_payment_number');
            $table->dropColumn('user_cost_number');
            $table->dropColumn('user_overdraft');
        });
    }
}
