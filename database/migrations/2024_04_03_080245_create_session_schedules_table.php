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
        Schema::create('session_schedules', function (Blueprint $table) {
            $table->id();
            // Add foreign key constraints
            $table->foreignId('seeker_id')->references('id')->on('seekers')->cascadeOnDelete();
            $table->foreignId('advisor_id')->references('id')->on('advisors')->cascadeOnDelete();
            $table->date('session_date');
            $table->time('start_time');
            $table->boolean('seeker_history')->default(true);
            $table->boolean('advisor_history')->default(true);
            $table->enum('advisor_approved',["Pennding","Accept","Not_Accept"])->default("Pennding");
            $table->string('linkseesion')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('session_schedules');
    }
};
