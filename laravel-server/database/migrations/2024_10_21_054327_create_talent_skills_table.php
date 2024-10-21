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
        Schema::create('talent_skills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('talent_id')->constrained('talents')->onDelete('cascade')->references('id');
            $table->foreignId('skill_id')->constrained('skills')->onDelete('cascade')->references('id');
            $table->integer('years_experience')->unsigned()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('talent_skills');
    }
};
