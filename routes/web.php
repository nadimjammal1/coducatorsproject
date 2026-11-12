<?php

use Illuminate\Support\Facades\Route;
use App\Models\Tournament; 
use Illuminate\Http\Request; 
use App\Http\Controllers\TournamentController;
Route::get('/', function () {
    return view('home');
});
Route::get('/tournaments', [TournamentController::class, 'index']);
#Route::get('/tournaments', function () {
    

    #$tournaments = Tournament::orderBy('created_at', 'desc')->get();
    #$tournaments = Tournament::where('status', 'active')->get();
   # $tournaments = Tournament::orderBy('start_date', 'asc')->get();
   # return view('tournaments', [
     #   'tournaments' => $tournaments
    #]);

#});

Route::get('/tournaments/create', function () {
    return view('create-tournament');
});


/*
Route::post('/tournaments', function (Request $request) {

    Tournament::create([
        'name' => $request->name,
        'game' => $request->game,
        'max_players' => $request->max_players,
        'start_date' => $request->start_date,
        'status' => $request->status,
    ]);

    return redirect('/tournaments');

});

Route::post('/tournaments', function (Request $request) {

    $validated = $request->validate([
        'name' => 'required|min:3|max:100',
        'game' => 'required|min:2|max:100',
        'max_players' => 'required|integer|min:2|max:64',
        'start_date' => 'nullable|date',
        'status' => 'required|in:upcoming,active,finished',
    ]);

    Tournament::create($validated);

    return redirect('/tournaments');

});
*/
Route::post('/tournaments', [TournamentController::class, 'store']);

Route::get('/tournaments/{tournament}', [TournamentController::class, 'show'])
    ->name('tournaments.show');

Route::post(
    '/tournaments/{tournament}/register',
    [TournamentController::class, 'registerPlayer']
)->name('tournaments.register');





// Create a match
Route::post(
    '/tournaments/{tournament}/matches',
    [TournamentController::class, 'storeMatch']
)->name('tournaments.matches.store');

// Save match scores
Route::put(
    '/matches/{match}/score',
    [TournamentController::class, 'updateMatchScore']
)->name('matches.score');





Route::post(
    '/tournaments/{tournament}/generate-bracket',
    [TournamentController::class, 'generateBracket']
)->name('tournaments.bracket.generate');


?>
