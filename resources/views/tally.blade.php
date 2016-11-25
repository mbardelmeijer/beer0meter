<h1>
    Beer0Meter
</h1>

<ul>
    @foreach($totals as $username => $count)
        <li>{{ $username }}: {{ $count }}</li>
    @endforeach
</ul>

<form method="post" action="/api/v1/tally">
    <select name="username">
        <option value="">== make a selection ==</option>
        @foreach($usernames as $username)
            <option>{{ $username }}</option>
        @endforeach
    </select>

    <input type="number" name="count" value="1">

    <button type="submit">Submit</button>
</form>

<table cellpadding="5">
    @foreach($tally as $stat)
        <tr>
            <td>{{ $stat['username'] }}</td>
            <td>{{ $stat['count'] }}</td>
            <td>{{ $stat['created_at'] }}</td>
        </tr>
    @endforeach
</table>
