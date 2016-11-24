<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

use Codesta\Beer0Meter\Tally;

app()->singleton(Tally::class);

$app->get('/', function () use ($app) {
    return view('tally', ['stats' => app(Tally::class)->stats()]);
});

$app->get('/api/v1/tally', function () use ($app) {
    $username = app('request')->get('username');
    if (empty($username)) {
        return ['error' => 'Username is required.'];
    }

    $count = (int) app('request')->get('count', 1);

    app(Tally::class)->add($username, $count);

    return ['total' => app(Tally::class)->countByUsername($username)];
});

$app->get('/api/v1/stats', function () use ($app) {
    return app(Tally::class)->stats();
});
