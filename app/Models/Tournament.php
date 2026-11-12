<?php

namespace App\Models;

use App\Models\Player;
use Illuminate\Database\Eloquent\Model;

class Tournament extends Model
{
    protected $fillable = [
        'name',
        'game',
        'max_players',
        'start_date',
        'status',
    ];

    public function players()
    {
        return $this->belongsToMany(Player::class)
            ->withTimestamps();
    }
    // A tournament can have many matches
    public function matches()
    {
        return $this->hasMany(TournamentMatch::class);
    }
}