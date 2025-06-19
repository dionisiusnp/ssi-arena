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
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('current_level')->default(1);
            $table->decimal('current_point', 6,2)->default(0);
            $table->boolean('nim')->nullable();
            $table->boolean('is_member')->nullable();
            $table->boolean('is_lecturer')->default(false);
            $table->boolean('is_active')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'current_level',
                'current_point',
                'nim',
                'is_member',
                'is_lecturer',
                'is_active',
            ]);
        });
    }
};
