<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bill_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('bill_id')->index();
            $table->integer('user_id');
            $table->string('tips');
            $table->unsignedDouble('cost');
            $table->timestamps();;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bill_users');
    }
}
