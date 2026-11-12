
<!DOCTYPE html>
<html>
<head>
    <title>{{ $tournament->name }}</title>
</head>

<body> 

<nav>
    <a href="/">Home</a> |
    <a href="/tournaments">Tournaments</a> |
    <a href="/tournaments/create">Create Tournament</a>
</nav>

<hr>

<h1>{{ $tournament->name }}</h1>

<p>
    <strong>Game:</strong>
    {{ $tournament->game }}
</p>

<p>
    <strong>Maximum Players:</strong>
    {{ $tournament->max_players }}
</p>

<p>
    <strong>Status:</strong>
    {{ $tournament->status }}
</p>

<p>
    <strong>Start Date:</strong>
    {{ $tournament->start_date ?? 'Not set' }}
</p>

<hr>

<!-- SUCCESS MESSAGE -->

@if (session('success'))
    <p style="color: green;">
        {{ session('success') }}
    </p>
@endif

<!-- ERROR MESSAGE -->

@if (session('error'))
    <p style="color: red;">
        {{ session('error') }}
    </p>
@endif

<!-- VALIDATION ERRORS -->

@if ($errors->any())
    <div style="color: red;">
        <p>Please fix the following errors:</p>

        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<hr>

<!-- REGISTER PLAYER -->

<h2>Register Player</h2>

<form method="POST"
      action="{{ route('tournaments.register', $tournament) }}">

    @csrf

    <label>Select Player:</label>

    <select name="player_id" required>
        <option value="">Choose Player</option>

        @foreach ($players as $player)
            <option value="{{ $player->id }}">
                {{ $player->username }}
            </option>
        @endforeach
    </select>

    <button type="submit">Register Player</button>

</form>

<!-- REGISTERED PLAYERS -->

<h2>Registered Players</h2>

@if ($tournament->players->count() > 0)

    <ul>
        @foreach ($tournament->players as $player)
            <li>
                {{ $player->username }} — {{ $player->name }}
            </li>
        @endforeach
    </ul>

@else
    <p>No players registered yet.</p>
@endif

<hr>

<!-- CREATE MATCH -->

<h2>Create Match</h2>

@if ($players->count() >= 2)

    <form method="POST"
          action="{{ route('tournaments.matches.store', $tournament) }}">

        @csrf

        <label>Player 1:</label>

        <select name="player_one_id" required>
            <option value="">Select Player</option>

            @foreach ($players as $player)
                <option value="{{ $player->id }}"
                    {{ old('player_one_id') == $player->id ? 'selected' : '' }}>
                    {{ $player->username }}
                </option>
            @endforeach
        </select>

        <br><br>

        <label>Player 2:</label>

        <select name="player_two_id" required>
            <option value="">Select Player</option>

            @foreach ($players as $player)
                <option value="{{ $player->id }}"
                    {{ old('player_two_id') == $player->id ? 'selected' : '' }}>
                    {{ $player->username }}
                </option>
            @endforeach
        </select>

        <br><br>

        <button type="submit">Create Match</button>

    </form>

@else
    <p>Register at least two players to create a match.</p>
@endif

<hr>

<!-- MATCH HISTORY -->

<h2>Match History</h2>

@if ($matches->count() > 0)

    @foreach ($matches as $match)

        <div style="border: 1px solid #ccc; padding: 15px; margin-bottom: 15px;">

            <h3>
                {{ $match->playerOne->username }}
                vs
                {{ $match->playerTwo->username }}
            </h3>

            <p>
                <strong>Status:</strong>
                {{ ucfirst($match->status) }}
            </p>

            @if ($match->status === 'completed')

                <p>
                    <strong>Score:</strong>
                    {{ $match->player_one_score }}
                    -
                    {{ $match->player_two_score }}
                </p>

                <p>
                    <strong>Winner:</strong>
                    {{ $match->winner?->username ?? 'Winner unavailable' }}
                </p>

            @else

                <p>Match not played yet.</p>

                <!-- ENTER MATCH SCORES -->

                <form method="POST"
                      action="{{ route('matches.score', $match) }}">

                    @csrf
                    @method('PUT')

                    <label>
                        {{ $match->playerOne->username }}'s score:
                    </label>

                    <input
                        type="number"
                        name="player_one_score"
                        min="0"
                        required
                    >

                    <br><br>

                    <label>
                        {{ $match->playerTwo->username }}'s score:
                    </label>

                    <input
                        type="number"
                        name="player_two_score"
                        min="0"
                        required
                    >

                    <br><br>

                    <button type="submit">Save Result</button>

                </form>

            @endif

        </div>

    @endforeach

@else

    <p>No matches created yet.</p>

@endif

<br>



<h2>Tournament Standings</h2>

@if ($standings->count() > 0)

    <table border="1" cellpadding="10">
        <thead>
            <tr>
                <th>Rank</th>
                <th>Player</th>
                <th>Played</th>
                <th>Wins</th>
                <th>Losses</th>
                <th>Points</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($standings as $index => $standing)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $standing['player']->username }}</td>
                    <td>{{ $standing['played'] }}</td>
                    <td>{{ $standing['wins'] }}</td>
                    <td>{{ $standing['losses'] }}</td>
                    <td>{{ $standing['points'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

@else

    <p>No standings available yet.</p>

@endif

<a href="/tournaments">Back to Tournaments</a>

</body>
</html>
