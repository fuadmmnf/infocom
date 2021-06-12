<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLeaderIdToDepartmentsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('departments', function (Blueprint $table) {
            $table->unsignedBigInteger('leader_id')->after('id')->nullable();

            $table->foreign('leader_id')->references('id')->on('support_agents');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('departments', function (Blueprint $table) {
            //
        });
    }
}
