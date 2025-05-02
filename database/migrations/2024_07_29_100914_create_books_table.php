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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->unsignedBigInteger('author_id');
            $table->bigInteger('number_of_pages');
            $table->string('published_date');
            $table->string('image');
            $table->text('description');
            $table->float('rating')->default(0);
            $table->bigInteger('ratings_count')->default(0);
            $table->string('isbn13')->nullable();
            $table->string('isbn10')-> nullable();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
