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
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('topic_id')->nullable()->constrained('topics');
            $table->string('language')->nullable();
            $table->string('name');
            $table->string('type_input'); // terminal, code_editor, link_video, text
            $table->text('content_input')->nullable();
            $table->string('type_output'); // terminal, code_editor, link_video, text
            $table->text('content_output')->nullable();
            $table->integer('sequence')->default(0);
            $table->string('visibility')->nullable(); //shared, draft, published
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
        Schema::dropIfExists('lessons');
    }
};
