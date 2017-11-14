<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('members', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('group_id');
            $table->unsignedInteger('connection_id')->nullable();
            $table->boolean('has_seen_connection')->default(false);
            $table->string('name');
            $table->string('slug');
            $table->timestamps();

            $table->foreign('group_id')->references('id')->on('groups')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('connection_id')->references('id')->on('members')->onUpdate('cascade')->onDelete('cascade');

            $table->index('slug');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropForeign('members_group_id_foreign');
            $table->dropForeign('members_connection_id_foreign');
        });
        Schema::dropIfExists('members');
    }
}
