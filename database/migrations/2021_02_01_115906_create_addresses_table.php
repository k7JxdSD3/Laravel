<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->increments('id');
			$table->unsignedInteger('user_id')->nullable();
			$table->string('name', 50)->nullable();
			$table->string('zip', 8)->nullable();
			$table->string('prefectures', 8)->nullable();
			$table->string('city', 24)->nullable();
			$table->string('address', 255)->nullable();
			$table->string('phone_number', 8)->nullable();
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
        Schema::dropIfExists('addresses');
    }
}
