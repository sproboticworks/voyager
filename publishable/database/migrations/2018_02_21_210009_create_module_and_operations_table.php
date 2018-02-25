<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
                $table->integer('parent_module_id')->unsigned();
                $table->string('code','50');
                $table->string('name','100');
                $table->timestamps();

                $table->foreign('parent_module_id')->references('id')->on('modules');
            });
        }

        if(!Schema::hasTable('operations')) {
            Schema::create('operations', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('parent_operation_id')->unsigned();
                $table->string('code','50');
                $table->string('name','100');
                $table->string('route','100');
                $table->enum('action',['INDEX', 'CREATE', 'STORE', 'SHOW', 'EDIT', 'UPDATE', 'DESTROY', 'DELETE', 'GET', 'ACCEPT', 'APPROVE', 'PUBLISH', 'SCHEDULE', 'CANCEL', 'UPLOAD']);
                $table->timestamps();

                $table->foreign('parent_operation_id')->references('id')->on('operations');
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
