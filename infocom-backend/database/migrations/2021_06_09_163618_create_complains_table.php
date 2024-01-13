<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComplainsTable extends Migration
{
    public function up()
    {
        Schema::create('complains', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('editor_id')->nullable();
            $table->unsignedBigInteger('agent_id')->nullable();
            $table->unsignedBigInteger('helptopic_id')->nullable();
            $table->unsignedBigInteger('department_id')->nullable();
            $table->unsignedBigInteger('slaplan_id')->nullable();
            $table->string('code');
            $table->enum('status', ['pending', 'assigned', 'working', 'finished', 'approved', 'overdue'])->default('pending');
            $table->enum('ticket_source', ['customer', 'agent', 'email'])->default('customer');
            $table->text('complain_text');
            $table->text('complain_summary')->nullable();
            $table->text('complain_feedback')->nullable();
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->nullable();
            $table->string('customer_file')->nullable();
            $table->string('feedback_file')->nullable();
            $table->string('customer_review')->default('');
            $table->integer('customer_rating')->nullable();
            $table->dateTimeTz('complain_time');
            $table->dateTimeTz('assigned_time')->nullable();
            $table->dateTimeTz('finished_time')->nullable();
            $table->dateTimeTz('approved_time')->nullable();
            $table->softDeletes();
            $table->timestamps();


            $table->foreign('customer_id')->references('id')->on('customers')->nullOnDelete();
            $table->foreign('agent_id')->references('id')->on('callcenter_agents')->nullOnDelete();
            $table->foreign('editor_id')->references('id')->on('support_agents')->nullOnDelete();
            $table->foreign('helptopic_id')->references('id')->on('help_topics')->nullOnDelete();
            $table->foreign('department_id')->references('id')->on('departments')->nullOnDelete();
            $table->foreign('slaplan_id')->references('id')->on('sla_plans')->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('complains');
    }
}
