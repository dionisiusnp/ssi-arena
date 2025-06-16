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
        Schema::create('quest_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('season_id')->nullable()->constrained('seasons');
            $table->foreignId('quest_type_id')->nullable()->constrained('quest_types');
            $table->foreignId('quest_level_id')->nullable()->constrained('quest_levels');
            $table->string('versus_type')->default('PVE');
            $table->json('claimable_by')->nullable();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('minimum_level')->default(1);
            $table->integer('point')->default(0);
            $table->decimal('point_multiple', 4, 2)->default(0);
            $table->decimal('point_total', 19, 2)->default(0);
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
        Schema::dropIfExists('quest_details');
    }
};
