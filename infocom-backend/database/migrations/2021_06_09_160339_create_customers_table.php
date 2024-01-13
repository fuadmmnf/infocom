<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration {
    public function up() {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('popaddress_id')->nullable();
            $table->json('services')->nullable();
            $table->string('type')->nullable();
            $table->string('connectivity_type')->nullable();
            $table->string('code')->nullable();
            $table->string('address')->default('');
            $table->text('technical_contact')->default('');
            $table->text('management_contact')->default('');
            $table->string('connection_package')->default('');
            $table->string('other_services')->default('');
            $table->string('connection_details')->default('');
            $table->string('additional_technical_box')->default('');
            $table->text('billing_information')->default('');
            $table->string('kam_name')->default('');
            $table->string('selling_price')->default('');
            $table->string('price_details')->default('');
            $table->text('nttn')->default('');
            $table->text('bw_allocation')->default('');
            $table->text('mrtg_details')->default('');
            $table->text('allocated_ip')->default('');
            $table->text('comment_box')->default('');
            $table->text('router_details')->default('');
            $table->dateTimeTz('installation_date')->nullable();
            $table->dateTimeTz('first_billing_date')->nullable();
            $table->string('additional_file')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('popaddress_id')->references('id')->on('pop_addresses')->cascadeOnDelete();
        });
    }

    public function down() {
        Schema::dropIfExists('customers');
    }
}
