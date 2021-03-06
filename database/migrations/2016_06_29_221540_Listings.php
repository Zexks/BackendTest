<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Listings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {

         Schema::create('listings', function (Blueprint $table) {
             $table->increments('id');
             $table->string('street')->unique();
             $table->string('city');
             $table->string('state');
             $table->integer('zip');
             $table->string('country');
             $table->double('price');
             $table->string('url');
             $table->integer('bed');
             $table->integer('bath');
             $table->string('key');
             $table->string('status');
             $table->boolean('disclose');
             $table->string('description');
             $table->string('mlsid');
             $table->integer('mlsnumber');
             $table->string('proptypeid');
             $table->string('categoryid');
         });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('listings');
    }
}
