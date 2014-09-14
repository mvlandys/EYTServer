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
            $game->played_at  = \DateTime::createFromFormat("Y-m-d H:i:s", $gameData["played_at"]);
            $game->age        = (empty($gameData["user_data"]["age"])) ? 0 : $gameData["user_data"]["age"];
            $game->dob        = (empty($gameData["user_data"]["dob"])) ? null : \DateTime::createFromFormat("d/m/Y", $gameData["user_data"]["dob"]);
            $game->save();

            for ($x = 1; $x < 4; $x++) {
                if (!empty($gameData["score_data"]["level-" . $x])) {
                    $cardScore            = new CardSortScore();
                    $cardScore->game_id   = $game->id;
                    $cardScore->level     = $x;
                    $cardScore->correct   = $gameData["score_data"]["level-" . $x]["Correct"];
                    $cardScore->incorrect = $gameData["score_data"]["level-" . $x]["Incorrect"];
                    $cardScore->save();
                }
            }
        }

        return CardSortScore::all();
    }

    public function showResults($test_name = null, $start = null, $end = null)
    {
        $games = $this->getGames($test_name, $start, $end);

        return View::make("cardsort/results", array(
            "games"     => $games,
            "test_name" => $test_name,
            "start"     => (!empty($start)) ? DateTime::createFromFormat("Y-m-d", $start)->format("d/m/Y") : null,
            "end"       => (!empty($end)) ? DateTime::createFromFormat("Y-m-d", $end)->format("d/m/Y") : null,
            "tests"     => CardSortGame::all(array("test_name"))
        ));
    }

    public function viewScores($game_id)
    {
        $scores = CardSortScore::where("game_id", "=", $game_id)->get();

        return View::make("cardsort/scores", array(
            "scores" => $scores
        ));
    }

    private function getGames($test_name = null, $start = null, $end = null)
    {
        if (!empty($test_name) && !empty($start) && !empty($end)) {
            $games = CardSortGame::where("test_name", "=", $test_name)->where("played_at", ">=", $start)->where("played_at", "<=", $end)->get();
        } else if (!empty($test_name)) {
            $games = CardSortGame::where("test_name", "=", $test_name)->get();
        } else {
            $games = CardSortGame::all();
        }

        return $games;
    }

    public function makeCSV($test_name = null, $start = null, $end = null)
    {
        $games = $this->getGames($test_name, $start, $end);
        $filename = date("U") . ".csv";

        $fp = fopen(public_path() . "/tmp/" . $filename, 'w');

        fputcsv($fp, array(
            "game_id",
            "subject_id",
            "session_id",
            "test_name",
            "grade",
            "dob",
            "age",
            "sex",
            "played_at",
            "level 1 correct",
            "level 1 incorrect",
            "level 2 correct",
            "level 2 incorrect",
            "level 3 correct",
            "level 3 incorrect",
        ));

        foreach($games as $game) {
            $scores = CardSortScore::where("game_id", "=", $game->id)->get();
            $scoreData = array();

            foreach($scores as $score) {
                $scoreData[$score->level]["correct"] = $score->correct;
                $scoreData[$score->level]["incorrect"] = $score->incorrect;
            }

            fputcsv($fp, array(
                $game->id,
                (empty($game->subject_id)) ? "." : $game->subject_id,
                (empty($game->session_id)) ? "." : $game->session_id,
                (empty($game->test_name)) ? "." : $game->test_name,
                (empty($game->grade)) ? "." : $game->grade,
                (empty($game->dob)) ? "." : $game->dob,
                (empty($game->age)) ? "." : $game->age,
                (empty($game->sex)) ? "." : $game->sex,
                (empty($game->played_at)) ? "." : $game->played_at,
                (empty($scoreData["1"]["correct"])) ? "." : $scoreData[1]["correct"],
                (empty($scoreData["1"]["incorrect"])) ? "." : $scoreData[1]["incorrect"],
                (empty($scoreData["2"]["correct"])) ? "." : $scoreData[2]["correct"],
                (empty($scoreData["2"]["incorrect"])) ? "." : $scoreData[2]["incorrect"],
                (empty($scoreData["3"]["correct"])) ? "." : $scoreData[3]["correct"],
                (empty($scoreData["3"]["incorrect"])) ? "." : $scoreData[3]["incorrect"],
            ));
        }

        fclose($fp);

        return View::make("csv", array(
            "filename" => $filename
        ));
    }
}