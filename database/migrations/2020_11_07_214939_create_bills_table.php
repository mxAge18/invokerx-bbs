<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBillsTable extends Migration 
{
	public function up()
	{
		Schema::create('bills', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('path');
            $table->text('tips');
            $table->integer('payment_user_id')->unsigned();
            $table->double('total_bill')->default(0);
            $table->double('bill_needs_average')->default(0);
            $table->integer('bill_group_id')->unsigned();
            $table->integer('is_needs_compute')->unsigned();
            $table->string('slug')->nullable();
            $table->timestamps();
        });
	}

	public function down()
	{
		Schema::drop('bills');
	}
}
