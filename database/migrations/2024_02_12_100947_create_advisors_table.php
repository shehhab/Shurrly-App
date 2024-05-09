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
        Schema::create('advisors', function (Blueprint $table) {
            $table->id();
            $table->string('bio');
            $table->integer('offere');
            $table->string('language');
            $table->string('country');
            $table->time('session_duration');
            $table->boolean('approved')->default(false);
            $table->foreignId('category_id')->nullable()->references('id')->on('categories');
            $table->foreignId('seeker_id')->references('id')->on('seekers');
            $table->timestamps();


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advisors');
    }
};
