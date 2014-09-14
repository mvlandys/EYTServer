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

            // Loop through each level
            for ($x = 1; $x < 4; $x++) {
                // Loop through the 6 tests
                $z = 0;
                for ($y = 1; $y < 7; $y++) {
                    if ($x < 3 && $z == 0) {
                        $y--;
                        $z++;
                    }

                    $cardScore            = new CardSortScore();
                    $cardScore->game_id   = $game->id;
                    $cardScore->level     = $x;
                    $cardScore->card      = $y;
                    $cardScore->value     = (isset($gameData["score_data"][$x . "-" . $y])) ? $gameData["score_data"][$x . "-" . $y] : 0;
                    $cardScore->save();

                    if ($x < 3 && $y == 5) {
                        $y = 7;
                    }
                }
            }
        }

        return array("success");//CardSortScore::all();
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
        $cards = array();

        // Loop through each level
        for ($x = 1; $x < 4; $x++) {
            // Loop through the 6 tests
            for ($y = 1; $y < 7; $y++) {
                $cards[] = "Level" . $x . "_" . $y . "Acc";
            }
        }

        // Response Time = RT

        fputcsv($fp, array_merge(array(
            "game_id",
            "subject_id",
            "session_id",
            "study_name",
            "grade",
            "DOB",
            "age",
            "sex",
            "DOT",
            "TOT"
        ), $cards));

        foreach($games as $game) {
            $scores = CardSortScore::where("game_id", "=", $game->id)->orderBy("level")->orderBy("card")->get();
            $scoreData = array();

            foreach($scores as $score) {
                $scoreData[] = $score->value;
            }

            $played_at = DateTime::createFromFormat("Y-m-d H:i:s", $game->played_at);

            fputcsv($fp, array_merge(array(
                $game->id,
                (empty($game->subject_id)) ? "." : $game->subject_id,
                (empty($game->session_id)) ? "." : $game->session_id,
                (empty($game->test_name)) ? "." : $game->test_name,
                (empty($game->grade)) ? "." : $game->grade,
                (empty($game->dob)) ? "." : $game->dob,
                (empty($game->age)) ? "." : $game->age,
                (empty($game->sex)) ? "." : $game->sex,
                (empty($game->played_at)) ? "." : $played_at->format("d/m/Y"),
                (empty($game->played_at)) ? "." : $played_at->format("H:i")
            ), $scoreData));
        }

        fclose($fp);

        return View::make("csv", array(
            "filename" => $filename
        ));
    }
}