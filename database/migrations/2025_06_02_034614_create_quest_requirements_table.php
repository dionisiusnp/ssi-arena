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
        Schema::create('quest_requirements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quest_detail_id')->nullable()->constrained('quest_details');
            $table->text('description')->nullable();
            $table->boolean('is_editable')->default(true);
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
        Schema::dropIfExists('quest_requirements');
    }
};
