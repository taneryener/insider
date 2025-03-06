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
                $table->integer('week');
                $table->integer('home_score');
                $table->integer('away_score');
                $table->foreignId('home_team_id')->constrained()->onDelete('cascade');
                $table->foreignId('away_team_id')->constrained()->onDelete('cascade');
                $table->enum('result', ['WIN', 'LOSE', 'DRAW'])->nullable();
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
