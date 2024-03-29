<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHelpTopicsTable extends Migration
{
	public function up()
	{
		Schema::create('help_topics', function (Blueprint $table) {
			$table->id();
			$table->string('name');
            $table->softDeletes();
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::dropIfExists('help_topics');
	}
}
