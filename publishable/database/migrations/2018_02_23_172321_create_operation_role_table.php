<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOperationRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('operation_role', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('role_id')->unsigned();
            $table->integer('operation_id')->unsigned();
            $table->integer('menu_item_id')->unsigned()->nullable();

            $table->foreign('role_id')->references('id')->on('roles');
            $table->foreign('operation_id')->references('id')->on('operations');
            $table->foreign('menu_item_id')->references('id')->on('menu_items');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('operation_role');
    }
}
