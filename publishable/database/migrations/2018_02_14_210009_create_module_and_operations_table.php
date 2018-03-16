<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use TCG\Voyager\Models\Operation;

class CreateModuleAndOperationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('modules')) {
            Schema::create('modules', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('parent_module_id')->unsigned()->nullable();
                $table->string('code','50');
                $table->string('name','100');
                $table->timestamps();

                $table->foreign('parent_module_id')->references('id')->on('modules');
            });
        }

        if(!Schema::hasTable('operations')) {
            Schema::create('operations', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('data_type_id')->unsigned()->nullable();
                $table->integer('parent_operation_id')->unsigned()->nullable();
                $table->string('code','50');
                $table->string('name','100');
                $table->string('route','100');
                $table->enum('action',Operation::ACTIONS);
                $table->timestamps();

                $table->foreign('parent_operation_id')->references('id')->on('operations');
                $table->foreign('data_type_id')->references('id')->on('data_types');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('modules');
        Schema::dropIfExists('operations');
    }
}
