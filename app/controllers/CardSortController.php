<?php

use Illuminate\Routing\Controller;

class CardSortController extends Controller
{

    public function saveGame()
    {
        if (!Input::has("games")) {
            return array("error" => "No Game Data specified");
        }

        $games = Input::get("games");

        foreach ($games as $gameData) {
            $game             = new CardSortGame();
            $game->subject_id = $gameData["user_data"]["subject_id"];
            $game->session_id = $gameData["user_data"]["session_id"];
            $game->grade      = $gameData["user_data"]["grade"];
            $game->sex        = $gameData["user_data"]["sex"];
            $game->test_name  = $gameData["user_data"]["test_name"];
            $game->played_at  = $gameData["played_at"];
            $game->age        = (empty($gameData["user_data"]["age"])) ? 0 : $gameData["user_data"]["age"];
            $game->dob        = (empty($gameData["user_data"]["dob"])) ? null : \DateTime::createFromFormat("d/m/Y", $gameData["user_data"]["dob"]);
            $game->save();

            // Level 1
            if (!empty($gameData["scoreData"]["level-1"])) {
                $cardScore            = new CardSortScore();
                $cardScore->game_id   = $game->id;
                $cardScore->level     = 1;
                $cardScore->correct   = $gameData["scoreData"]["level-1"]["Correct"];
                $cardScore->incorrect = $gameData["scoreData"]["level-1"]["Incorrect"];
                $cardScore->save();
            }

            // Level 2
            if (!empty($gameData["scoreData"]["level-2"])) {
                $cardScore            = new CardSortScore();
                $cardScore->game_id   = $game->id;
                $cardScore->level     = 1;
                $cardScore->correct   = $gameData["scoreData"]["level-2"]["Correct"];
                $cardScore->incorrect = $gameData["scoreData"]["level-2"]["Incorrect"];
                $cardScore->save();
            }

            // Level 3
            if (!empty($gameData["scoreData"]["level-3"])) {
                $cardScore            = new CardSortScore();
                $cardScore->game_id   = $game->id;
                $cardScore->level     = 1;
                $cardScore->correct   = $gameData["scoreData"]["level-3"]["Correct"];
                $cardScore->incorrect = $gameData["scoreData"]["level-3"]["Incorrect"];
                $cardScore->save();
            }
        }

        return array("success");
    }

    public function showResults($test_name = null, $start = null, $end = null)
    {
        $games = CardSortGame::all();

        return View::make("cardsort/results", array(
            "games" => $games
        ));
    }

    public function viewScores($game_id)
    {
        $scores = CardSortScore::where("game_id", "=", $game_id)->orderBy("card", "ASC")->get();

        return View::make("cardsort/scores", array(
            "scores" => $scores
        ));
    }
}