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
});

Route::post("/vocab/save", "VocabController@saveGames");
Route::get("/vocab/game/{id}", "VocabController@viewScores");
Route::get("/vocab", "VocabController@showResults");

Route::get("/questions", "QuestionsController@displayForm");

Route::post("/cardsort/save", "CardSortController@saveGame");
Route::get("/cardsort/game/{id}", "CardSortController@viewScores");
Route::get("/cardsort", "CardSortController@showResults");