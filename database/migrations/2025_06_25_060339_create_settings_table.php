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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('group');
            $table->integer('sequence')->default(0);
            $table->string('key')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('column_type');
            $table->string('select_endpoint')->nullable();
            $table->string('select_label')->nullable();
            $table->text('default_value')->nullable();
            $table->text('current_value')->nullable();
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
        Schema::dropIfExists('settings');
    }
};
