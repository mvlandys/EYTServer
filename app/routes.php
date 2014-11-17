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
Route::get("/support", function () {
    return View::make("support");
});

// Login Routes
Route::get("/login", function () {
    return View::make("login");
});
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
    // Default Route
    Route::any('/', function () {
        return View::make("layout");
    });

    // Vocab Routes
    Route::group(array("before" => "vocab"), function () {
        Route::get("/vocab/game/{id}/delete", array("before" => "delete", "uses" => "VocabController@deleteGame"));
        Route::get("/vocab/game/{id}", "VocabController@viewScores");
        Route::get("/vocab/csv/{test_name}/{start}/{end}", "VocabController@makeCSV");
        Route::get("/vocab/csv/{test_name}", "VocabController@makeCSV");
        Route::get("/vocab/csv", "VocabController@makeCSV");
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
        Route::get("/notthis/csv", "NotThisController@makeCSV");
        Route::get("/notthis/{test_name}", "NotThisController@showResults");
        Route::get("/notthis/{test_name}/{start}/{end}", "NotThisController@showResults");
        Route::get("/notthis", "NotThisController@showResults");
    });

    // Admin Routes
    Route::group(array("before" => "admin"), function () {
        Route::get("/admin/users", "UserController@listUsers");
        Route::get("/admin/newuser", "UserController@newUser");
        Route::post("/admin/newuser/submit", "UserController@submitNewUser");
        Route::get("/admin/user/{user_id}", "UserController@viewUser");
        Route::post("/admin/user/{user_id}/update", "UserController@updateUser");
    });
});

// App POST routes
Route::post("/vocab/save", "VocabController@saveGames");
Route::post("/cardsort/save", "CardSortController@saveGame");
Route::post("/questionnaire/save", "QuestionnaireController@saveAnswers");
Route::post("/mrant/save", "MrAntController@saveAnswers");
Route::post("/fishshark/save", "FishSharkController@saveGames");
Route::post("/notthis/save", "NotThisController@saveGames");

// Remove Duplicates
Route::get("/duplicate_fix", function () {
    $games = MrAntGame::all();

    // Loop through each game
    foreach ($games as $game) {
        if (empty(MrAntGame::find($game->id)->id)) {
            continue;
        }

        $duplicate = MrAntGame::where("id", "!=", $game->id)
            ->where("subject_id", "=", $game->subject_id)
            ->where("session_id", "=", $game->session_id)
            ->where("test_name", "=", $game->test_name)
            ->where("grade", "=", $game->grade)
            ->where("dob", "=", $game->dob)
            ->where("age", "=", $game->age)
            ->where("sex", "=", $game->sex)
            ->where("played_at", "=", $game->played_at)
            ->where("score", "=", $game->score);

        foreach ($duplicate->get() as $gameData) {
            MrAntScore::where("game_id", "=", $gameData->id)->delete();
        }

        $duplicate->delete();
    }

    echo "Done";
});

Route::get("/duplicate_fix2", function () {
    $games = FishSharkGame::all();

    // Loop through each game
    foreach ($games as $game) {
        if (empty(FishSharkGame::find($game->id)->id)) {
            continue;
        }

        $duplicate = FishSharkGame::where("id", "!=", $game->id)
            ->where("subject_id", "=", $game->subject_id)
            ->where("session_id", "=", $game->session_id)
            ->where("test_name", "=", $game->test_name)
            ->where("grade", "=", $game->grade)
            ->where("dob", "=", $game->dob)
            ->where("age", "=", $game->age)
            ->where("sex", "=", $game->sex)
            ->where("played_at", "=", $game->played_at);

        foreach ($duplicate->get() as $gameData) {
            FishSharkScore::where("game_id", "=", $gameData->id)->delete();
        }

        $duplicate->delete();
    }

    echo "Done";
});