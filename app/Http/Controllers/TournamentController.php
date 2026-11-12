<?php
namespace App\Http\Controllers;

use App\Models\Player;
use App\Models\Tournament;
use App\Models\TournamentMatch;
use Illuminate\Http\Request;

class TournamentController extends Controller
{
    // Display all tournaments
    public function index()
    {
        $tournaments = Tournament::orderBy(
            'created_at',
            'desc'
        )->get();

        return view('tournaments', [
            'tournaments' => $tournaments
        ]);
    }

    // Save a new tournament
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|min:3|max:100',
            'game' => 'required|min:2|max:100',
            'max_players' => 'required|integer|min:2|max:64',
            'start_date' => 'nullable|date',
            'status' => 'required|in:upcoming,active,finished',
        ]);

        Tournament::create($validated);

        return redirect('/tournaments');
    }

    // Display one tournament and its players and matches
   public function show(Tournament $tournament)
{
    // 1. Get registered players
    $players = $tournament->players()
        ->orderBy('username')
        ->get();

    // 2. Get all matches for match history
    $matches = $tournament->matches()
        ->latest()
        ->get();

    // 3. Get only completed matches for standings
    $completedMatches = $tournament->matches()
        ->where('status', 'completed')
        ->get();

    // 4. Calculate each player's statistics
    $standings = $players->map(function ($player) use ($completedMatches) {

        $matchesPlayed = $completedMatches
            ->filter(function ($match) use ($player) {
                return (int) $match->player_one_id === (int) $player->id
                    || (int) $match->player_two_id === (int) $player->id;
            })
            ->count();

        $wins = $completedMatches
            ->filter(function ($match) use ($player) {
                return (int) $match->winner_id === (int) $player->id;
            })
            ->count();

        $losses = $matchesPlayed - $wins;

        $points = $wins * 3;

        return [
            'player' => $player,
            'played' => $matchesPlayed,
            'wins' => $wins,
            'losses' => $losses,
            'points' => $points,
        ];
    });

    // 5. Sort from highest to lowest points
    $standings = $standings
        ->sortByDesc('points')
        ->values();

    // 6. Send the data to the Blade view
    return view('tournament-show', [
        'tournament' => $tournament,
        'players' => $players,
        'matches' => $matches,
        'standings' => $standings,
    ]);
}

    // Register a player in a tournament
    public function registerPlayer(
        Request $request,
        Tournament $tournament
    ) {
        $validated = $request->validate([
            'player_id' => 'required|exists:players,id',
        ]);

        $tournament->players()->syncWithoutDetaching([
            $validated['player_id']
        ]);

        return back()->with(
            'success',
            'Player registered successfully!'
        );
    }

    // Create a match between two registered players
    public function storeMatch(
        Request $request,
        Tournament $tournament
    ) {
        $validated = $request->validate([
            'player_one_id' => 'required|exists:players,id',
            'player_two_id' => 'required|exists:players,id|different:player_one_id',
        ]);

        // Make sure both players belong to this tournament
        $playerOneIsRegistered = $tournament->players()
            ->whereKey($validated['player_one_id'])
            ->exists();

        $playerTwoIsRegistered = $tournament->players()
            ->whereKey($validated['player_two_id'])
            ->exists();

        if (!$playerOneIsRegistered || !$playerTwoIsRegistered) {
            return back()
                ->withErrors([
                    'player_one_id' =>
                        'Both players must be registered in this tournament.'
                ])
                ->withInput();
        }

        TournamentMatch::create([
            'tournament_id' => $tournament->id,
            'player_one_id' => $validated['player_one_id'],
            'player_two_id' => $validated['player_two_id'],
            'status' => 'scheduled',
        ]);

        return back()->with(
            'success',
            'Match created successfully!'
        );
    }

    // Save scores and determine the winner
    public function updateMatchScore(
        Request $request,
        TournamentMatch $match
    ) {
        $validated = $request->validate([
            'player_one_score' => 'required|integer|min:0',
            'player_two_score' => 'required|integer|min:0',
        ]);

        $scoreOne = (int) $validated['player_one_score'];
        $scoreTwo = (int) $validated['player_two_score'];

        if ($scoreOne === $scoreTwo) {
            return back()->with(
                'error',
                'The match cannot end in a draw.'
            );
        }

        $winnerId = $scoreOne > $scoreTwo
            ? $match->player_one_id
            : $match->player_two_id;

        $match->update([
            'player_one_score' => $scoreOne,
            'player_two_score' => $scoreTwo,
            'winner_id' => $winnerId,
            'status' => 'completed',
        ]);

        return back()->with(
            'success',
            'Match result saved!'
        );
    }








    public function generateBracket(Tournament $tournament)
{
    // Get all registered players in random order
    $players = $tournament->players()
        ->inRandomOrder()
        ->get();

    // This bracket currently requires exactly 4 players
    if ($players->count() !== 4) {
        return back()->with(
            'error',
            'This bracket currently requires exactly 4 players.'
        );
    }

    // Don't generate the bracket twice
    if ($tournament->matches()->exists()) {
        return back()->with(
            'error',
            'Bracket has already been generated.'
        );
    }

    // Create Semi Final 1
    TournamentMatch::create([
        'tournament_id' => $tournament->id,
        'player_one_id' => $players[0]->id,
        'player_two_id' => $players[1]->id,
        'round' => 'semi_final',
        'status' => 'scheduled',
    ]);

    // Create Semi Final 2
    TournamentMatch::create([
        'tournament_id' => $tournament->id,
        'player_one_id' => $players[2]->id,
        'player_two_id' => $players[3]->id,
        'round' => 'semi_final',
        'status' => 'scheduled',
    ]);

    return back()->with(
        'success',
        'Semi-finals generated!'
    );
}                   

}








