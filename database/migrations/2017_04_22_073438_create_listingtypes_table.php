<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateListingtypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('listingtypes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('listingtype_name',50);
            $table->text('listingtype_description')->nullable();
            //cara buat foreign key::
            // $table->integer('state_id')->unsigned();
            // $table->foreign('state_id')->references('id')->on('states');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('listingtypes');
    }
}
