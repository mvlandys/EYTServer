<?php

use Illuminate\Routing\Controller;

class VocabController extends Controller
{

    public function saveGames()
    {
        if (!Input::has("games")) {
            return array("error" => "No Game Data specified");
        }

        $games = Input::get("games");

        foreach ($games as $gameData) {
            $game             = new VocabGame();
            $game->subject_id = $gameData["user_data"]["subject_id"];
            $game->session_id = $gameData["user_data"]["session_id"];
            $game->grade      = $gameData["user_data"]["grade"];
            $game->sex        = $gameData["user_data"]["sex"];
            $game->test_name  = $gameData["user_data"]["test_name"];
            $game->played_at  = $gameData["played_at"];

            $game->age = (empty($gameData["user_data"]["age"])) ? 0 : $gameData["user_data"]["age"];
            $game->dob = (empty($gameData["user_data"]["dob"])) ? null : \DateTime::createFromFormat("d/m/Y", $gameData["user_data"]["dob"]);

            $score = 0;

            foreach ($gameData["score_data"] as $gameScore) {
                $score += $gameScore;
            }

            $game->score = $score;
            $game->save();

            foreach ($gameData["score_data"] as $card => $value) {
                $card      = substr($card, 5);
                $cardScore = new VocabScore();

                $cardScore->game_id    = $game->id;
                $cardScore->card       = $card;
                $cardScore->value      = ($value == 1) ? 1 : 0;
                $cardScore->additional = ($value != 0 || $value != 1) ? $value : 0;
                $cardScore->save();
            }
        }

        return array("success");
    }

    public function showResults($test_name = null, $start = null, $end = null)
    {
        if (!empty($test_name) && !empty($start) && !empty($end)) {
            $games = VocabGame::where("test_name", "=", $test_name)->where("played_at", ">=", $start)->where("played_at", "<=", $end)->get();
        } else if (!empty($test_name)) {
            $games = VocabGame::where("test_name", "=", $test_name)->get();
        } else {
            $games = VocabGame::all();
        }

        return View::make("vocab/results", array(
            "games"     => $games,
            "test_name" => $test_name,
            "tests"      => VocabGame::all(array("test_name"))
        ));
    }

    public function viewScores($game_id)
    {
        $scores = VocabScore::where("game_id", "=", $game_id)->orderBy("card", "ASC")->get();

        return View::make("vocab/scores", array(
            "scores" => $scores
        ));
    }
}