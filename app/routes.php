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


Route::get("/support", function() {
    return View::make("support");
});

Route::get("/login", function() {
    return View::make("login");
});
Route::post("/login/submit", "UserController@login");
Route::get("/logout", "UserController@logout");

// Questionnaire Web Form
Route::get("/questionnaire/form", "QuestionnaireController@showForm");
Route::post("/questionnaire/form/submit", "QuestionnaireController@submitForm");

Route::group(array("before" => "auth"), function()
{
    // Default Route
    Route::any('/', function()
    {
        return View::make("layout");
    });

    // Vocab Routes
    Route::get("/vocab/game/{id}", "VocabController@viewScores");
    Route::get("/vocab/csv", "VocabController@makeCSV");
    Route::get("/vocab/{test_name}", "VocabController@showResults");
    Route::get("/vocab/{test_name}/{start}/{end}", "VocabController@showResults");
    Route::get("/vocab", "VocabController@showResults");

    // CardSort Routes
    Route::get("/cardsort/game/{id}", "CardSortController@viewScores");
    Route::get("/cardsort/csv", "CardSortController@makeCSV");
    Route::get("/cardsort/{test_name}", "CardSortController@showResults");
    Route::get("/cardsort/{test_name}/{start}/{end}", "CardSortController@showResults");
    Route::get("/cardsort", "CardSortController@showResults");

    // Questionnaire Routes
    Route::get("/questionnaire", "QuestionnaireController@showResults");
    Route::get("/questionnaire/game/{id}", "QuestionnaireController@viewScores");
    Route::get("/questionnaire/csv", "QuestionnaireController@makeCSV");

    // Questionnaire Routes
    Route::get("/mrant", "MrAntController@showResults");
    Route::get("/mrant/game/{id}", "MrAntController@viewScores");
    Route::get("/mrant/csv", "MrAntController@makeCSV");
});

// App POST routes
Route::post("/vocab/save", "VocabController@saveGames");
Route::post("/cardsort/save", "CardSortController@saveGame");
Route::post("/questionnaire/save", "QuestionnaireController@saveAnswers");
Route::post("/mrant/save", "MrAntController@saveAnswers");