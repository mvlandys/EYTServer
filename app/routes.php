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



Route::get("/login", function() {
    return View::make("login");
});
Route::post("/login/submit", "UserController@login");
Route::get("/logout", "UserController@logout");

Route::group(array("before" => "auth"), function()
{
    // Default Route
    Route::any('/', function()
    {
        return View::make("layout");
    });

    // Vocab Routes
    Route::get("/vocab/game/{id}", "VocabController@viewScores");
    Route::get("/vocab/{test_name}", "VocabController@showResults");
    Route::get("/vocab/{test_name}/{start}/{end}", "VocabController@showResults");
    Route::get("/vocab", "VocabController@showResults");

    // CardSort Routes
    Route::get("/cardsort/game/{id}", "CardSortController@viewScores");
    Route::get("/cardsort/{test_name}", "CardSortController@showResults");
    Route::get("/cardsort", "CardSortController@showResults");

    // Questionnaire Routes
    Route::get("/questions", "QuestionsController@displayForm");
});

// App POST routes
Route::post("/vocab/save", "VocabController@saveGames");
Route::post("/cardsort/save", "CardSortController@saveGame");