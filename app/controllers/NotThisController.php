<?php

class NotThisController extends BaseController
{
    public function saveGames()
    {
        if (!Input::has("games")) {
            return array("error" => "No Game Data specified");
        }

        // Log game data
        Mail::send('email_log', array(), function ($message) {
            $message->to(["mvlandys@gmail.com"])->subject("NotThis Log " . date("H:i:s d/m/Y"));
        });

        $games = Input::get("games");

        foreach ($games as $gameData) {
            $game             = new NotThisGame();
            $game->subject_id = $gameData["subject_id"];
            $game->session_id = $gameData["session"];
            $game->grade      = $gameData["grade"];
            $game->sex        = $gameData["sex"];
            $game->test_name  = (empty($gameData["test_name"])) ? "" : $gameData["test_name"];
            $game->played_at  = \DateTime::createFromFormat("Y-m-d H:i", $gameData["date"]);
            $game->age        = (empty($gameData["age"])) ? 0 : $gameData["age"];
            $game->dob        = (empty($gameData["birthdate"])) ? null : \DateTime::createFromFormat("d/m/Y", $gameData["birthdate"]);
            $game->score      = 0;
            $game->save();

            foreach ($gameData["tries"] as $score) {
                $gameScore               = new NotThisScore();
                $gameScore->game_id      = $game->id;
                $gameScore->set          = $score["setNumber"];
                $gameScore->rep          = $score["repNumber"];
                $gameScore->correct      = $score["correct"];
                $gameScore->responseTime = $score["responseTime"];
                $gameScore->attempted    = $score["attempted"];
                $gameScore->save();

                if ($score["correct"] == 1) {
                    $game->score++;
                }
            }

            $game->save();
        }

        return array("success");
    }

    public function showResults($test_name = null, $start = null, $end = null)
    {
        $games     = $this->getGames($test_name, $start, $end);
        $tests     = NotThisGame::all(array("test_name"))->toArray();
        $testNames = array();

        foreach ($tests as $test) {
            if (!isset($testNames[$test["test_name"]])) {
                $testNames[str_replace("+", "%20", urlencode($test["test_name"]))] = $test;
            }
        }

        return View::make("notthis/results", array(
            "games"     => $games,
            "test_name" => $test_name,
            "start"     => (!empty($start)) ? DateTime::createFromFormat("Y-m-d", $start)->format("d/m/Y") : null,
            "end"       => (!empty($end)) ? DateTime::createFromFormat("Y-m-d", $end)->format("d/m/Y") : null,
            "tests"     => $testNames
        ));
    }

    public function viewScores($game_id)
    {
        $scores = NotThisScore::where("game_id", "=", $game_id)->orderBy("rep", "ASC")->get();

        return View::make("notthis/scores", array(
            "scores" => $scores
        ));
    }

    public function makeCSV($test_name = null, $start = null, $end = null)
    {
        $games    = $this->getGames($test_name, $start, $end);
        $filename = date("U") . ".csv";

        $fp    = fopen(public_path() . "/tmp/" . $filename, 'w');
        $cards = array();

        // Loop through each set
        for ($x = 1; $x < 9; $x++) {
            // Loop through the 5 reps
            for ($y = 1; $y < 6; $y++) {
                $cards[] = "Set" . $x . "_" . $y . "Acc";
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

        foreach ($games as $game) {
            $scores    = NotThisScore::where("game_id", "=", $game->id)->orderBy("set")->orderBy("rep")->get();
            $scoreData = array();

            $set = 1;
            $rep = 1;

            foreach ($scores as $score) {
                $scoreData[] = ($score->correct == 0) ? "." : 1;
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
                (empty($game->played_at)) ? "." : $played_at->format("H:i"),
            ), $scoreData));
        }

        fclose($fp);

        return View::make("csv", array(
            "filename" => $filename
        ));
    }

    private function getGames($test_name = null, $start = null, $end = null)
    {
        $order = (Input::has("order")) ? Input::get("order") : "played_at";

        if (!empty($test_name) && !empty($start) && !empty($end)) {
            $games = ($test_name == "all")
                ? NotThisGame::where("played_at", ">=", $start)->where("played_at", "<=", $end)->orderBy($order, "DESC")->get()
                : NotThisGame::where("test_name", "=", $test_name)->where("played_at", ">=", $start)->where("played_at", "<=", $end)->orderBy($order, "DESC")->get();
        } else if (!empty($test_name)) {
            $games = NotThisGame::where("test_name", "=", $test_name)->orderBy($order, "DESC")->get();
        } else {
            $games = NotThisGame::orderBy($order, "DESC")->get();
        }

        return $games;
    }

    public function deleteGame($game_id)
    {
        NotThisScore::where("game_id", "=", $game_id)->delete();
        NotThisGame::where("id", "=", $game_id)->delete();

        return ["success" => true];
    }
}