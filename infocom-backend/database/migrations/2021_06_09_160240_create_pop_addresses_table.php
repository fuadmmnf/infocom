<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePopAddressesTable extends Migration {
	public function up() {
		Schema::create('pop_addresses', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->string('name');
            $table->softDeletes();
			$table->timestamps();
		});
	}

	public function down() {
		Schema::dropIfExists('pop_addresses');
	}
}
