<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCallcenterAgentsTable extends Migration
{
	public function up()
	{
		Schema::create('callcenter_agents', function (Blueprint $table) {
			$table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
        });
	}

	public function down()
	{
		Schema::dropIfExists('callcenter_agents');
	}
}
