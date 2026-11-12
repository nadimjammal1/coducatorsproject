<?php

namespace App\Models;

use App\Models\Tournament;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    protected $fillable = [
        'name',
        'username',
        'email',
    ];

    public function tournaments()
    {
        return $this->belongsToMany(Tournament::class)
            ->withTimestamps();
    }
    // A tournament can have many matches
    public function matches()
    {
        return $this->hasMany(TournamentMatch::class);
    }
}