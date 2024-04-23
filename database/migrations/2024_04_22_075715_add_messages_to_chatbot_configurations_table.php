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
        Schema::table('chatbot_configurations', function (Blueprint $table) {
            $table->string('message1')->nullable();
            $table->string('message2')->nullable();
            $table->string('message3')->nullable();
            $table->string('message4')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('chatbot_configurations', function (Blueprint $table) {
            $table->dropColumn('message1');
            $table->dropColumn('message2');
            $table->dropColumn('message3');
            $table->dropColumn('message4');
        });
    }
};
