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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('content');
            $table->string('image');
            $table->timestamp('published_at');

            $table->foreignId('source_id')->constrained('sources')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('author_id')->constrained('authors')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('category_id')->constrained('categories')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
