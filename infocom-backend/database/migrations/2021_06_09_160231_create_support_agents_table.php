<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupportAgentsTable extends Migration
{
	public function up()
	{
		Schema::create('support_agents', function (Blueprint $table) {
			$table->id();
			$table->unsignedBigInteger('user_id');
			$table->unsignedBigInteger('department_id')->nullable();
            $table->softDeletes();
			$table->timestamps();


            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('department_id')->references('id')->on('departments')->nullOnDelete();
		});
	}

	public function down()
	{
		Schema::dropIfExists('support_agents');
	}
}
