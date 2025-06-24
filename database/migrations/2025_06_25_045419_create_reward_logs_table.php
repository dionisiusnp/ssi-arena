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
        Schema::create('reward_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quest_detail_id')->constrained('quest_details');
            $table->foreignId('activity_id')->constrained('activities');
            $table->foreignId('season_id')->constrained('seasons');
            $table->foreignId('user_id')->constrained('users');
            $table->integer('get_level');
            $table->integer('get_poin');
            $table->string('status');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reward_logs');
    }
};
