<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerMessagesTable extends Migration
{
    public function up()
    {
        Schema::create('customer_messages', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['individual', 'popaddreess', 'total']);
            $table->json('customers');
            $table->text('message');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('customer_messages');
    }
}
