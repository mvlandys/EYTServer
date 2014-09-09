<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::any('/', function()
{
    return Input::all();
	//return View::make('layout');
});

Route::get("/test2", "TestController@index");
Route::any("/test1", "TestController@newGame");