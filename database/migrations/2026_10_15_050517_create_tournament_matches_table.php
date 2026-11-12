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


    Schema::create('tournament_matches', function (Blueprint $table) {

        // Unique ID for each match
        $table->id();

        // Which tournament this match belongs to
        $table->foreignId('tournament_id')
            ->constrained()
            ->cascadeOnDelete();

        // The first player in the match
        $table->foreignId('player_one_id')
            ->constrained('players')
            ->cascadeOnDelete();

        // The second player in the match
        $table->foreignId('player_two_id')
            ->constrained('players')
            ->cascadeOnDelete();

        // Scores are empty until the match is played
        $table->integer('player_one_score')->nullable();
        $table->integer('player_two_score')->nullable();

        // The winner is unknown until the result is saved
        $table->foreignId('winner_id')
            ->nullable()
            ->constrained('players')
            ->nullOnDelete();

        // A new match has not been played yet
        $table->string('status')->default('scheduled');

        // Created and updated timestamps
        $table->timestamps();
    });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tournament_matches');
    }
};
