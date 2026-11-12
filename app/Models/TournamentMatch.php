<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TournamentMatch extends Model
{
    protected $fillable = [
        'tournament_id',
        'player_one_id',
        'player_two_id',
        'player_one_score',
        'player_two_score',
        'winner_id',
        'status',
        'round',
    ];

    // A match belongs to one tournament
    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }

    // Get the first player
    public function playerOne()
    {
        return $this->belongsTo(Player::class, 'player_one_id');
    }

    // Get the second player
    public function playerTwo()
    {
        return $this->belongsTo(Player::class, 'player_two_id');
    }

    // Get the winning player
    public function winner()
    {
        return $this->belongsTo(Player::class, 'winner_id');
    }
}
