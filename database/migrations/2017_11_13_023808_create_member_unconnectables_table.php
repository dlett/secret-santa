<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMemberUnconnectablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_unconnectables', function (Blueprint $table) {
            $table->unsignedInteger('member_id');
            $table->unsignedInteger('unconnectable_id');

            $table->foreign('member_id')->references('id')->on('members')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('unconnectable_id')->references('id')->on('members')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('member_unconnectables', function (Blueprint $table) {
            $table->dropForeign('member_unconnectables_member_id_foreign');
            $table->dropForeign('member_unconnectables_unconnectable_id_foreign');
        });

        Schema::dropIfExists('member_unconnectables');
    }
}
