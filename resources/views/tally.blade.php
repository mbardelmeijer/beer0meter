<h1>
    Beer0Meter
</h1>

<ul>
    @foreach($stats as $username => $count)
        <li>{{ $username }}: {{ $count }}</li>
    @endforeach
</ul>
