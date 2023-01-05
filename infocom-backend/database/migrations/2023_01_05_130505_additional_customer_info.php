<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('client_type')->after('popaddress_id')->nullable();
            $table->string('connection_type')->after('popaddress_id')->nullable();
            $table->string('bandwidth_distribution_point')->after('popaddress_id')->nullable();
            $table->string('connectivity_type')->after('popaddress_id')->nullable();
            $table->double('bandwidth_allocation')->after('popaddress_id')->nullable();
            $table->string('allocated_ip')->after('popaddress_id')->nullable();
            $table->string('selling_price_bdt_excluding_vat')->after('popaddress_id')->nullable();

        });
    }

    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            //
        });
    }
};
