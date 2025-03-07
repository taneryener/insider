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
        Schema::create('fixture',
            function (Blueprint $table) {
                $table->id();
                $table->foreignId('home_team_id')->constrained('teams','id')->onDelete('cascade');
                $table->foreignId('away_team_id')->constrained('teams','id')->onDelete('cascade');
                $table->integer('home_score')->nullable();
                $table->integer('away_score')->nullable();
                $table->integer('week');
                $table->tinyInteger('result')->nullable(); // 1 home 0 draw 2 away
                $table->timestamps();
                $table->softDeletes();
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fixture');
    }
};
