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

// Static Pages
Route::get("/support", "HomeController@supportPage");

// Login Routes
Route::get("/login", "UserController@loginPage");
Route::post("/login/submit", "UserController@login");
Route::get("/logout", "UserController@logout");

// Password Reset Routes
Route::get("/passwordreset/request", "UserController@requestPasswordReset");
Route::post("/passwordreset/request/submit", "UserController@submitPasswordResetRequest");
Route::get("/passwordreset/{code}", "UserController@resetPassword");
Route::post("/passwordreset/submit", "UserController@processResetPassword");

// Questionnaire Web Form
Route::get("/questionnaire/form", "QuestionnaireController@showForm");
Route::post("/questionnaire/form/submit", "QuestionnaireController@submitForm");

Route::group(array("before" => "auth"), function () {
    // Home Route
    Route::get('/home', "HomeController@homePage");
    Route::get("/csv/{test_name}/{start}/{end}", "HomeController@makeCSV");
    Route::get("/csv/{test_name}", "HomeController@makeCSV");
    Route::get("/csv", "HomeController@makeCSV");

    // Vocab Routes
    Route::group(array("before" => "vocab"), function () {
        /*
        Route::get("/vocab/new", "VocabController@showResultsNew");
        Route::get("/vocab/new/duplicates", "VocabController@fixDuplicatesNew");
        Route::get("/vocab/new/game/{id}", "VocabController@viewScoresNew");
        Route::get("/vocab/new/csv/{test_name}", "VocabController@makeCSVNew");
        Route::get("/vocab/new/csv", "VocabController@makeCSVNew");
        Route::get("/vocab/new/{test_name}", "VocabController@showResultsNew");
        */

        Route::get("/vocab/game/{id}/delete", array("before" => "delete", "uses" => "VocabController@deleteGame"));
        Route::get("/vocab/game/{id}", "VocabController@viewScores");
        Route::get("/vocab/csv/{test_name}/{start}/{end}", "VocabController@makeCSV");
        Route::get("/vocab/csv/{test_name}", "VocabController@makeCSV");
        Route::get("/vocab/csv", "VocabController@makeCSV");
        Route::get("/vocab/duplicates", "VocabController@fixDuplicates");
        Route::get("/vocab/{test_name}/{start}/{end}", "VocabController@showResults");
        Route::get("/vocab/{test_name}", "VocabController@showResults");
        Route::get("/vocab", "VocabController@showResults");
    });

    // CardSort Routes
    Route::group(array("before" => "cardsort"), function () {
        Route::get("/cardsort/game/{id}/delete", array("before" => "delete", "uses" => "CardSortController@deleteGame"));
        Route::get("/cardsort/game/{id}", "CardSortController@viewScores");
        Route::get("/cardsort/csv/{test_name}/{start}/{end}", "CardSortController@makeCSV");
        Route::get("/cardsort/csv/{test_name}", "CardSortController@makeCSV");
        Route::get("/cardsort/csv", "CardSortController@makeCSV");
        Route::get("/cardsort/duplicates", "CardSortController@fixDuplicates");
        Route::get("/cardsort/{test_name}", "CardSortController@showResults");
        Route::get("/cardsort/{test_name}/{start}/{end}", "CardSortController@showResults");
        Route::get("/cardsort", "CardSortController@showResults");
    });

    // Questionnaire Routes
    Route::group(array("before" => "questionnaire"), function () {
        Route::get("/questionnaire/game/{id}/delete", array("before" => "delete", "uses" => "QuestionnaireController@deleteGame"));
        Route::get("/questionnaire", "QuestionnaireController@showResults");
        Route::get("/questionnaire/game/{id}", "QuestionnaireController@viewScores");
        Route::get("/questionnaire/csv", "QuestionnaireController@makeCSV");
    });

    // MrAnt Routes
    Route::group(array("before" => "mrant"), function () {
        Route::get("/mrant/game/{id}/delete", array("before" => "delete", "uses" => "MrAntController@deleteGame"));
        Route::get("/mrant/game/{id}", "MrAntController@viewScores");
        Route::get("/mrant/csv/{test_name}/{start}/{end}", "MrAntController@makeCSV");
        Route::get("/mrant/csv/{test_name}", "MrAntController@makeCSV");
        Route::get("/mrant/duplicates", "MrAntController@fixDuplicates");
        Route::get("/mrant/csv", "MrAntController@makeCSV");
        Route::get("/mrant/{test_name}", "MrAntController@showResults");
        Route::get("/mrant/{test_name}/{start}/{end}", "MrAntController@showResults");
        Route::get("/mrant", "MrAntController@showResults");
    });

    // Fish Shark Routes
    Route::group(array("before" => "fishshark"), function () {
        Route::get("/fishshark/game/{id}/delete", array("before" => "delete", "uses" => "FishSharkController@deleteGame"));
        Route::get("/fishshark/game/{id}", "FishSharkController@viewScores");
        Route::get("/fishshark/csv/{test_name}/{start}/{end}", "FishSharkController@makeCSV");
        Route::get("/fishshark/csv/{test_name}", "FishSharkController@makeCSV");
        Route::get("/fishshark/duplicates", "FishSharkController@fixDuplicates");
        Route::get("/fishshark/csv", "FishSharkController@makeCSV");
        Route::get("/fishshark/{test_name}", "FishSharkController@showResults");
        Route::get("/fishshark/{test_name}/{start}/{end}", "FishSharkController@showResults");
        Route::get("/fishshark", "FishSharkController@showResults");
    });

    // NotThis Routes
    Route::group(array("before" => "notthis"), function () {
        Route::get("/notthis/game/{id}/delete", array("before" => "delete", "uses" => "NotThisController@deleteGame"));
        Route::get("/notthis/game/{id}", "NotThisController@viewScores");
        Route::get("/notthis/csv/{test_name}/{start}/{end}", "NotThisController@makeCSV");
        Route::get("/notthis/csv/{test_name}", "NotThisController@makeCSV");
        Route::get("/notthis/duplicates", "NotThisController@fixDuplicates");
        Route::get("/notthis/csv", "NotThisController@makeCSV");
        Route::get("/notthis/{test_name}", "NotThisController@showResults");
        Route::get("/notthis/{test_name}/{start}/{end}", "NotThisController@showResults");
        Route::get("/notthis", "NotThisController@showResults");
    });

    // ECERS Routes
    Route::group(array("before" => "ecers"), function () {
        Route::get("/ecers/entry/{entry_id}", "EcersController@viewEntry");
        Route::get("/ecers/game/{entry_id}/delete", "EcersController@deleteEntry");
        Route::get("/ecers/csv/{test_name}", "EcersController@makeCSV");
        Route::get("/ecers/csv/{test_name}/{start}/{end}", "EcersController@makeCSV");
        Route::get("/ecers/csv", "EcersController@makeCSV");
        Route::get("/ecers/duplicates", "EcersController@fixDuplicates");
        Route::get("/ecers/{test_name}", "EcersController@showResults");
        Route::get("/ecers/{test_name}/{start}/{end}", "EcersController@showResults");
        Route::get("/ecers", "EcersController@showResults");
    });

    // Admin Routes
    Route::group(array("before" => "admin"), function () {
        Route::get("/admin/users/delete/{user_id}", "UserController@deleteUser");
        Route::get("/admin/users", "UserController@listUsers");
        Route::get("/admin/newuser", "UserController@newUser");
        Route::post("/admin/newuser/submit", "UserController@submitNewUser");
        Route::get("/admin/user/{user_id}", "UserController@viewUser");
        Route::post("/admin/user/{user_id}/update", "UserController@updateUser");
        Route::get("/admin/apps", "UserController@listAppUsers");
        Route::get("/admin/newappuser", "UserController@newAppUser");
        Route::post("/admin/newappuser/submit", "UserController@addAppUser");
        Route::get("/admin/appuser/password/{id}/{password}", "UserController@passwordAppUser");
    });
});

// App POST routes
Route::get("/vocab/copy/{date}", "VocabController@migrateOldToNew");
//Route::post("/vocab/new/save", "VocabController@saveGamesNew");
Route::post("/vocab/save", "VocabController@saveGames");
Route::post("/cardsort/save", "CardSortController@saveGame");
Route::post("/questionnaire/save", "QuestionnaireController@saveAnswers");
Route::post("/mrant/save", "MrAntController@saveAnswers");
Route::post("/fishshark/save", "FishSharkController@saveGames");
Route::post("/notthis/save", "NotThisController@saveGames");
Route::post("/ecers/save", "EcersController@saveEntries");

Route::get("/", function() {
    if (!Session::has("user_id")) {
        return Redirect::to('http://www.eytoolbox.com.au');
    } else {
        return Redirect::to('/home');
    }
});