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
        Schema::create('activity_checklists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quest_requirement_id')->nullable()->constrained('quest_requirements');
            $table->foreignId('activity_id')->nullable()->constrained('activities');
            $table->boolean('is_clear')->default(true);
            $table->foreignId('changed_by')->nullable()->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_checklists');
    }
};
