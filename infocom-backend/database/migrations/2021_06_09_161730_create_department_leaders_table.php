<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepartmentLeadersTable extends Migration
{
	public function up()
	{
		Schema::create('department_leaders', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->unsignedBigInteger('department_id');
			$table->unsignedBigInteger('leader_id');
			$table->timestamps();

            $table->foreign('department_id')->references('id')->on('departments');
            $table->foreign('leader_id')->references('id')->on('support_agents');
        });
	}

	public function down()
	{
		Schema::dropIfExists('department_leaders');
	}
}
