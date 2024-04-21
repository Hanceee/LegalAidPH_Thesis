<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('chatbot_configurations', function (Blueprint $table) {
            $table->string('model_name')->nullable();
            $table->string('model_details')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chatbot_configurations', function (Blueprint $table) {
            $table->dropColumn('model_name');
        $table->dropColumn('model_details');
        });
    }
};
