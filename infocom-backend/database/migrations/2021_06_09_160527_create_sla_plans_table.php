<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSlaPlansTable extends Migration {
    public function up() {
        Schema::create('sla_plans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->unique();
            $table->double('timelimit')->nullable();
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('sla_plans');
    }
}
