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
        Schema::table('tasks', function (Blueprint $table) {
            $table->dateTime('reminder')->nullable();
            $table->string('color', 20)->nullable();
            $table->string('emoji', 20)->nullable();
            $table->boolean('pinned')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn(['reminder', 'color', 'emoji', 'pinned']);
        });
    }
};
