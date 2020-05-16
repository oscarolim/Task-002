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

$router->get('/', function () use ($router) {
    return '
    <h2>User</h2>
    <h3>Does not require authentication (for ease of testing the API - real world scenario would require authentication for at least all write requests)</h3>
    <p>GET api/v1/user [Optional <strong>extra</strong> parameter with <strong>championships</strong> as the value to return associated championships</p>
    <p>GET api/v1/user/{id} [Optional <strong>extra</strong> parameter with <strong>championships</strong> as the value to return associated championships</p>
    <p>POST api/v1/user/{id} [name:string, email:valid email, number:int >= 0]</p>
    <h3>Requires authentication (Api-Key parameter in header)</h3>
    <p>PUT api/v1/user/{id} [name:string, email:valid email, number:int >= 0]</p>
    <p>DELETE api/v1/user/{id}</p>

    <h2>Championship</h2>
    <h3>Requires authentication (Api-Key parameter in header)</h3>
    <p>GET api/v1/championship [Optional <strong>extra</strong> parameter with <strong>participants</strong> and/or <strong>races</strong> (comma separated) as the value to return associated participants</p>
    <p>GET api/v1/championship/{id} [Optional <strong>extra</strong> parameter with <strong>participants</strong> and/or <strong>races</strong> (comma separated) as the value to return associated participants</p>
    <p>POST api/v1/championship/{id} [name:string, date:valid date (YYYY-MM-DD)]</p>
    <p>PUT api/v1/championship/{id} [name:string, date:valid date (YYYY-MM-DD)]</p>
    <p>DELETE api/v1/championship/{id}</p>
    <p>POST api/v1/championship/{id}/participant [user_id: valid user id]</p>
    <p>DELETE api/v1/championship/{id}/participant [user_id: valid user id]</p>

    <h2>Races</h2>
    <h3>Requires authentication (Api-Key parameter in header)</h3>
    <p>GET api/v1/race</p>
    <p>GET api/v1/race/{id}</p>
    <p>POST api/v1/race/{id} [name:string, championship_id:valid championship_id]</p>
    <p>PUT api/v1/race/{id} [name:string, championship_id:valid championship_id]</p>
    <p>DELETE api/v1/race/{id}</p>
    <p>POST api/v1/race/{id}/result [user_id: valid user idp, points: integer]</p>
    ';
});

$router->group([
    'prefix' => 'api/v1',
], function () use ($router){
    $router->get('/user', 'UserController@index');
    $router->get('/user/{id}', 'UserController@show');
    $router->post('/user', 'UserController@store');
    $router->put('/user/{id}', [
        'middleware' => 'auth',
        'uses' => 'UserController@update'
    ]);
    $router->delete('/user/{id}', [
        'middleware' => 'auth',
        'uses' => 'UserController@destroy'
    ]);
});

$router->group([
    'prefix' => 'api/v1',
    'middleware' => 'auth'
], function () use ($router){
    $router->get('/championship', 'ChampionshipController@index');
    $router->get('/championship/{id}', 'ChampionshipController@show');
    $router->post('/championship', 'ChampionshipController@store');
    $router->put('/championship/{id}', 'ChampionshipController@update');
    $router->delete('/championship/{id}', 'ChampionshipController@destroy');
    $router->post('/championship/{id}/participant', 'ChampionshipController@addParticipant');
    $router->delete('/championship/{id}/participant', 'ChampionshipController@removeParticipant');
});

$router->group([
    'prefix' => 'api/v1',
    'middleware' => 'auth'
], function () use ($router){
    $router->get('/race', 'RaceController@index');
    $router->get('/race/{id}', 'RaceController@show');
    $router->post('/race', 'RaceController@store');
    $router->put('/race/{id}', 'RaceController@update');
    $router->delete('/race/{id}', 'RaceController@destroy');
    $router->post('/race/{id}/result', 'RaceController@updateResult');
});