<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComplainsTable extends Migration {
    public function up() {
        Schema::create('complains', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('editor_id')->nullable();
            $table->unsignedBigInteger('helptopic_id');
            $table->unsignedBigInteger('department_id')->nullable();
            $table->unsignedBigInteger('slaplan_id')->nullable();
            $table->enum('status', ['pending', 'assigned', 'working', 'finished', 'approved'])->default('pending');
            $table->enum('ticket_source', ['customer', 'agent', 'email'])->default('customer');
            $table->text('complain_text');
            $table->string('complain_summary')->default('');
            $table->string('complain_feedback')->default('');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('low');
            $table->dateTimeTz('complain_time')->default(\Carbon\Carbon::now());
            $table->dateTimeTz('deleated_at')->nullable();
            $table->timestamps();


            $table->foreign('customer_id')->references('id')->on('customers');
            $table->foreign('editor_id')->references('id')->on('support_agents');
            $table->foreign('helptopic_id')->references('id')->on('help_topics');
            $table->foreign('department_id')->references('id')->on('departments');
            $table->foreign('slaplan_id')->references('id')->on('sla_plans');
        });
    }

    public function down() {
        Schema::dropIfExists('complains');
    }
}
