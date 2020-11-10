<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bill_groups', function (Blueprint $table) {
            $table->id();
            $table->string('group_name');
            $table->timestamps();
            $table->foreign('bill_group_id')->references('id')->on('bill_group_user')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bill_groups', function (Blueprint $table) {
            // 移除外键约束
            $table->dropForeign(['bill_group_id']);
        });
        Schema::dropIfExists('bill_group');
    }
}
