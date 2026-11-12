<!DOCTYPE html>
<html>

<head>
    <title>Tournaments</title>
</head>

<body>

    <nav>
        <a href="/">Home</a>
        <a href="/tournaments">Tournaments</a>
        <a href="/tournaments/create">Create Tournament</a>
        <a href="/tournaments/show">Show Tournaments</a>
    </nav>

    <h1>All Tournaments</h1>

    @if ($tournaments->count() > 0)

        @foreach ($tournaments as $tournament)

            <div style="border:1px solid red; padding:15px; margin:15px 0">
                <h2>{{ $tournament->name }}</h2>

                <p>
                    Game:
                    {{ $tournament->game }}
                </p>

                <p>
                    Max Players:
                    {{ $tournament->max_players }}
                </p>

                <p>
                    Status:
                    {{ $tournament->status }}
                </p>

                <p>
                    Start Date:
                    {{ $tournament->start_date ?? 'Not set' }}
                </p>
            </div>

            <hr>

        @endforeach

    @else

        <p>No tournaments yet.</p>

    @endif

</body>

</html>