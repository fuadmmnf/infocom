<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration {
    public function up() {
        Schema::create('customers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->string('code')->nullable();
            $table->string('address')->default('');
            $table->string('technical_contact')->default('');
            $table->string('management_contact')->default('');
            $table->string('connection_package')->default('');
            $table->string('other_services')->default('');
            $table->string('connection_details')->default('');
            $table->string('additional_technical_box')->default('');
            $table->string('billing_information')->default('');
            $table->string('kam_name')->default('');
            $table->dateTimeTz('installation_date')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    public function down() {
        Schema::dropIfExists('customers');
    }
}
