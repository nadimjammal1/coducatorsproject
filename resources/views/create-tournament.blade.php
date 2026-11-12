<!DOCTYPE html>
<html>

<head>
    <title>Create Tournament</title>
</head>

<body>

    <nav>
        <a href="/">Home</a>
        <a href="/tournaments">Tournaments</a>
        <a href="/tournaments/create">Create Tournament</a>
    </nav>

    <h1>Create Tournament</h1>

    <form method="POST" action="/tournaments">

        @csrf

        <label>Tournament Name</label>
        <br>

        <input
            type="text"
            name="name"
            value="{{ old('name') }}"
        >

        <br><br>

        <label>Game</label>
        <br>

        <input
            type="text"
            name="game"
            value="{{ old('game') }}"
        >

        <br><br>

        <label>Max Players</label>
        <br>

        <input
            type="number"
            name="max_players"
            value="{{ old('max_players') }}"
        >

        <br><br>

        <label>Start Date</label>
        <br>

        <input
            type="date"
            name="start_date"
            value="{{ old('start_date') }}"
        >

        <br><br>

        <label>Status</label>
        <br>

        <select name="status">

            <option value="upcoming">
                Upcoming
            </option>

            <option value="active">
                Active
            </option>

            <option value="finished">
                Finished
            </option>

        </select>

        <br><br>

        <button type="submit">
            Create Tournament
        </button>

    </form>

    <br>

    <a href="/tournaments">
        Back to Tournaments
    </a>

</body>

</html>