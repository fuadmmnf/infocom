<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSlaPlansTable extends Migration {
    public function up() {
        Schema::create('sla_plans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('helptopic_id');
            $table->string('name');
            $table->double('timelimit')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('helptopic_id')->references('id')->on('help_topics')->cascadeOnDelete();
        });
    }

    public function down() {
        Schema::dropIfExists('sla_plans');
    }
}
