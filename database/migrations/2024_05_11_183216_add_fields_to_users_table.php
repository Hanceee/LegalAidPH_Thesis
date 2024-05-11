<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('users', function (Blueprint $table) {
        $table->string('topic')->nullable();
        $table->text('message1')->nullable();
        $table->text('message2')->nullable();
        $table->text('message3')->nullable();
        $table->text('message4')->nullable();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down()
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn(['topic', 'message1', 'message2', 'message3', 'message4']);
    });
}

};
