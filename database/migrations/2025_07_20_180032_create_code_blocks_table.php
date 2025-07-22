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
        Schema::create('code_blocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('changed_by')->constrained('users');
            $table->text('code_content');
            $table->string('language')->nullable(); // e.g., 'php', 'javascript', 'dart'
            $table->string('description')->nullable(); // Optional description for the code block
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('code_blocks');
    }
};
