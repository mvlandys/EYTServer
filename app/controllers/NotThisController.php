<?php

class NotThisController extends BaseController
{
    public function saveGames()
    {
        if (!Input::has("games")) {
            return array("error" => "No Game Data specified");
        }

        //return Input::get("games");

        // Log game data
        Mail::send('email_log', array(), function ($message) {
            $message->to(["mathew@icrm.net.au"])->subject("NotThis Log " . date("H:i:s d/m/Y"));
        });

        $games = Input::get("games");

        foreach ($games as $gameData) {
            $game             = new NotThisGame();
            $game->subject_id = $gameData["subject_id"];
            $game->session_id = $gameData["session"];
            $game->grade      = $gameData["grade"];
            $game->sex        = $gameData["sex"];
            $game->test_name  = (empty($gameData["test_name"])) ? "Untitled Test" : $gameData["test_name"];
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
                $gameScore->attempted    = 1; //$score["attempted"];
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
        $gameRep   = new Games(new NotThisGame());
        $games     = $gameRep->getGames($test_name, $start, $end);
        $tests     = App::make('perms');
        $testNames = array();

        foreach ($tests as $test) {
            $key = str_replace("+", "%20", urlencode($test->test_name));
            if (!isset($testNames[$key])) {
                $testNames[$key] = $test;
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
        $scores = NotThisScore::where("game_id", "=", $game_id)->orderBy("set", "ASC")->orderBy("rep", "ASC")->get();

        return View::make("notthis/scores", array(
            "scores" => $scores
        ));
    }

    public function makeCSV($test_name = null, $start = null, $end = null, $returnFile = false)
    {
        $gameRep  = new Games(new NotThisGame());
        $games    = $gameRep->getGames($test_name, $start, $end);
        $filename = "notthis_" . date("U") . ".csv";

        $fp    = fopen(public_path() . "/tmp/" . $filename, 'w');
        $cards = array();

        // Loop through each set
        for ($x = 1; $x < 9; $x++) {
            // Loop through the 5 reps
            for ($y = 1; $y < 6; $y++) {
                $cards[] = "Level" . $x . "_" . $y . "Acc";
            }
        }

        // Loop through each set
        for ($x = 1; $x < 9; $x++) {
            // Loop through the 5 reps
            for ($y = 1; $y < 6; $y++) {
                $cards[] = "Level" . $x . "_" . $y . "Resp";
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
            $scores    = NotThisScore::where("game_id", "=", $game->id)->get(); //->orderBy("set", "ASC")->orderBy("rep", "ASC")->get();
            $scoreData = array();

            foreach ($scores as $score) {
                $value = $score->correct;

                // Set 1, Rep 1, Response Time = 0
                if ($score->set == 1 && $score->rep == 1 && $score->responseTime == 0) {
                    // Do Nothing
                // All other sets/reps where Response Time = 0
                } else if ($score->set > 1 && $score->responseTime == 0) {
                    $value = ".";
                }

                $scoreData[] = $value;
            }

            foreach ($scores as $score) {
                $scoreData[] = ($score->responseTime == 0 || empty($score->responseTime)) ? "." : $score->responseTime;
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

        if ($returnFile == true) {
            return $filename;
        } else {
            return View::make("csv", array(
                "filename" => $filename
            ));
        }
    }

    public function deleteGame($game_id)
    {
        NotThisScore::where("game_id", "=", $game_id)->delete();
        NotThisGame::where("id", "=", $game_id)->delete();

        return ["success" => true];
    }

    public function fixDuplicates()
    {
        $games = NotThisGame::all();

        // Loop through each game
        foreach ($games as $game) {
            if (empty(NotThisGame::find($game->id)->id)) {
                continue;
            }

            $duplicate = NotThisGame::where("id", "!=", $game->id)
                ->where("subject_id", "=", $game->subject_id)
                ->where("session_id", "=", $game->session_id)
                ->where("test_name", "=", $game->test_name)
                ->where("grade", "=", $game->grade)
                ->where("dob", "=", $game->dob)
                ->where("age", "=", $game->age)
                ->where("sex", "=", $game->sex)
                ->where("played_at", "=", $game->played_at);

            foreach ($duplicate->get() as $gameData) {
                NotThisScore::where("game_id", "=", $gameData->id)->delete();
            }

            $duplicate->delete();
        }

        echo "Done";
    }

    public function deleteGames()
    {
        $games   = Input::get("game_ids");
        $gameRep = new Games(new NotThisGame());
        return $gameRep->deleteGames(new NotThisScore(), $games);
    }
}