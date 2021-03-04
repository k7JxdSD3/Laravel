<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('payments', function (Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('user_id')->nullable();
			$table->string('description')->nullable();
			$table->unsignedInteger('amount')->default(0);
			$table->string('charge_id')->nullable();
			$table->string('name', 50)->nullable();
			$table->string('zip', 8)->nullable();
			$table->string('prefectures', 8)->nullable();
			$table->string('city', 24)->nullable();
			$table->string('address', 255)->nullable();
			$table->string('phone_number', 8)->nullable();
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
		Schema::dropIfExists('payments');
	}
}
