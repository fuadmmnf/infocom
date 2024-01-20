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
            $table->string('client_type')->nullable();
            $table->string('connectivity_type')->nullable();
            $table->string('customer_id')->nullable();
            $table->text('address')->default('');
            $table->text('technical_contact')->nullable();
            $table->text('management_contact')->nullable();
            $table->text('billing_contact_person')->nullable();
            $table->text('kam_name')->default('');
            $table->text('selling_price')->default('');
            $table->text('price_details')->default('');
            $table->text('nttn')->nullable();
            $table->text('bw_allocation')->nullable();
            $table->text('mrtg_details')->nullable();
            $table->text('allocated_ip')->nullable();
            $table->text('comment_box')->nullable();
            $table->text('router_details')->nullable();
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
