<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_next_of_kins', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained();
            $table->string("firstname")->nullable();
            $table->string("lastname")->nullable();
            $table->string("email")->nullable();
            $table->date("date_of_birth")->nullable();
            $table->enum("relationship", ["Father", "Mother", "Son", "Daughter", "Brother", "Sister", "Friend"])->nullable();
            $table->string("house_address")->nullable();
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
        Schema::dropIfExists('user_next_of_kins');
    }
};
