<?php

class MrAntController extends BaseController
{
    public function saveAnswers()
    {
        /*
        if (!Input::has("games")) {
            return array("error" => "No Game Data specified");
        }

        // Log game data
        Mail::send('email_log', array(), function ($message) {
            $message->to(["mvlandys@gmail.com"])->subject("MrAnt Log " . date("H:i:s d/m/Y"));
        });

        $games = Input::get("games");*/
        $games = Input::all();

        foreach ($games as $gameData) {
            $duplicate = MrAntGame::where("subject_id", "=", $gameData["subject_id"])
                ->where("session_id", "=", $gameData["session"])
                ->where("test_name", "=", $gameData["studyName"])
                ->where("grade", "=", $gameData["grade"])
                ->where("dob", "=", $gameData["birthdate"])
                ->where("age", "=", $gameData["age"])
                ->where("sex", "=", $gameData["sex"])
                ->count();

            if ($duplicate > 0) {
                continue;
            }

            $game = MrAntGame::create(array(
                "subject_id"    => $gameData["subject_id"],
                "session_id"    => $gameData["session"],
                "test_name"     => $gameData["studyName"],
                "grade"         => $gameData["grade"],
                "dob"           => $gameData["birthdate"],
                "age"           => $gameData["age"],
                "sex"           => $gameData["sex"],
                "played_at"     => $gameData["date"] . ":00",
                "score"         => (! empty($gameData["score"])) ? $gameData["score"] : "",
                "ts_start"      => (empty($gameData["timestamps"]["Start"])) ? null : date("Y-m-d H:i:s", strtotime($gameData["timestamps"]["Start"])),
                "ts_lvl1_start" => (empty($gameData["timestamps"]["Level 1 Start"])) ? null : date("Y-m-d H:i:s", strtotime($gameData["timestamps"]["Level 1 Start"])),
                "ts_lvl1_end"   => (empty($gameData["timestamps"]["Level 1 End"])) ? null : date("Y-m-d H:i:s", strtotime($gameData["timestamps"]["Level 1 End"])),
                "ts_lvl2_start" => (empty($gameData["timestamps"]["Level 2 Start"])) ? null : date("Y-m-d H:i:s", strtotime($gameData["timestamps"]["Level 2 Start"])),
                "ts_lvl2_end"   => (empty($gameData["timestamps"]["Level 2 End"])) ? null : date("Y-m-d H:i:s", strtotime($gameData["timestamps"]["Level 2 End"])),
                "ts_lvl3_start" => (empty($gameData["timestamps"]["Level 3 Start"])) ? null : date("Y-m-d H:i:s", strtotime($gameData["timestamps"]["Level 3 Start"])),
                "ts_lvl3_end"   => (empty($gameData["timestamps"]["Level 3 End"])) ? null : date("Y-m-d H:i:s", strtotime($gameData["timestamps"]["Level 3 End"])),
                "ts_lvl4_start" => (empty($gameData["timestamps"]["Level 4 Start"])) ? null : date("Y-m-d H:i:s", strtotime($gameData["timestamps"]["Level 4 Start"])),
                "ts_lvl4_end"   => (empty($gameData["timestamps"]["Level 4 End"])) ? null : date("Y-m-d H:i:s", strtotime($gameData["timestamps"]["Level 4 End"])),
                "ts_lvl5_start" => (empty($gameData["timestamps"]["Level 5 Start"])) ? null : date("Y-m-d H:i:s", strtotime($gameData["timestamps"]["Level 5 Start"])),
                "ts_lvl5_end"   => (empty($gameData["timestamps"]["Level 5 End"])) ? null : date("Y-m-d H:i:s", strtotime($gameData["timestamps"]["Level 5 End"])),
                "ts_lvl6_start" => (empty($gameData["timestamps"]["Level 6 Start"])) ? null : date("Y-m-d H:i:s", strtotime($gameData["timestamps"]["Level 6 Start"])),
                "ts_lvl6_end"   => (empty($gameData["timestamps"]["Level 6 End"])) ? null : date("Y-m-d H:i:s", strtotime($gameData["timestamps"]["Level 6 End"])),
                "ts_lvl7_start" => (empty($gameData["timestamps"]["Level 7 Start"])) ? null : date("Y-m-d H:i:s", strtotime($gameData["timestamps"]["Level 7 Start"])),
                "ts_lvl7_end"   => (empty($gameData["timestamps"]["Level 7 End"])) ? null : date("Y-m-d H:i:s", strtotime($gameData["timestamps"]["Level 7 End"])),
                "ts_lvl8_start" => (empty($gameData["timestamps"]["Level 8 Start"])) ? null : date("Y-m-d H:i:s", strtotime($gameData["timestamps"]["Level 8 Start"])),
                "ts_lvl8_end"   => (empty($gameData["timestamps"]["Level 8 End"])) ? null : date("Y-m-d H:i:s", strtotime($gameData["timestamps"]["Level 8 End"]))
            ));

            foreach ($gameData["tries"] as $score) {
                MrAntScore::create(array(
                    "game_id"      => $game->id,
                    "level"        => $score["setNumber"],
                    "part"         => $score["repNumber"],
                    "value"        => $score["correct"],
                    "responseTime" => $score["responseTime"]
                ));
            }
        }

        return array("success");
    }

    public function showResults($test_name = null, $start = null, $end = null)
    {
        $games     = $this->getGames($test_name, $start, $end);
        $tests     = MrAntGame::all(array("test_name"))->toArray();
        $testNames = array();

        foreach ($tests as $test) {
            if (!isset($testNames[$test["test_name"]])) {
                $testNames[str_replace("+", "%20", urlencode($test["test_name"]))] = $test;
            }
        }

        return View::make("mrant/results", array(
            "games"     => $games,
            "test_name" => $test_name,
            "start"     => (!empty($start)) ? DateTime::createFromFormat("Y-m-d", $start)->format("d/m/Y") : null,
            "end"       => (!empty($end)) ? DateTime::createFromFormat("Y-m-d", $end)->format("d/m/Y") : null,
            "tests"     => $testNames
        ));
    }

    private function getGames($test_name = null, $start = null, $end = null)
    {
        $order = (Input::has("order")) ? Input::get("order") : "played_at";

        if (!empty($test_name) && !empty($start) && !empty($end)) {
            $games = ($test_name == "all")
                ? MrAntGame::where("played_at", ">=", $start)->where("played_at", "<=", $end)->orderBy($order, "DESC")->get()
                : MrAntGame::where("test_name", "=", $test_name)->where("played_at", ">=", $start)->where("played_at", "<=", $end)->orderBy($order, "DESC")->get();
        } else if (!empty($test_name) && $test_name != "all") {
            $games = MrAntGame::where("test_name", "=", $test_name)->orderBy($order, "DESC")->get();
        } else {
            $games = MrAntGame::orderBy($order, "DESC")->get();
        }

        return $games;
    }

    public function viewScores($game_id)
    {
        $scores = MrAntScore::where("game_id", "=", $game_id)->orderBy("level", "ASC")->orderBy("part", "ASC")->get();

        return View::make("mrant/scores", array(
            "scores" => $scores
        ));
    }

    public function makeCSV($test_name = null, $start = null, $end = null)
    {
        $games    = $this->getGames($test_name, $start, $end);
        $filename = date("U") . ".csv";

        $fp         = fopen(public_path() . "/tmp/" . $filename, 'w');
        $gamesCount = array();

        for ($x = 1; $x < 9; $x++) {
            for ($y = 1; $y < 4; $y++) {
                $gamesCount[] = "Level" . $x . "_" . $y . "_Acc";
            }
        }

        for ($x = 1; $x < 9; $x++) {
            for ($y = 1; $y < 4; $y++) {
                $gamesCount[] = "Level" . $x . "_" . $y . "_RT";
            }
        }

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
            "score",
            "TS_Start",
            "TS_Lvl1_Start",
            "TS_Lvl1_End",
            "TS_Lvl2_Start",
            "TS_Lvl2_End",
            "TS_Lvl3_Start",
            "TS_Lvl3_End",
            "TS_Lvl4_Start",
            "TS_Lvl4_End",
            "TS_Lvl5_Start",
            "TS_Lvl5_End",
            "TS_Lvl6_Start",
            "TS_Lvl6_End",
            "TS_Lvl7_Start",
            "TS_Lvl7_End",
            "TS_Lvl8_Start",
            "TS_Lvl8_End"
        ), $gamesCount));

        foreach ($games as $game) {
            $scores = array();

            $gameScores = array();
            foreach(MrAntScore::where("game_id", "=", $game->id) as $gameScore) {
                if (empty($gameScores[$gameScore->level])) {
                    $gameScores[$gameScore->level] = array();
                }

                $gameScores[$gameScore->level][$gameScore->part] = $gameScore;
            }

            for ($x = 1; $x < 9; $x++) {
                for ($y = 1; $y < 4; $y++) {
                    $score    = (isset($gameScores[$x][$y])) ? $gameScores[$x][$y] : null;
                    $scores[] = ($score != null && isset($score->value) && $score->responseTime != "0") ? $score->value : ".";
                }
            }

            for ($x = 1; $x < 9; $x++) {
                for ($y = 1; $y < 4; $y++) {
                    $score    = (isset($gameScores[$x][$y])) ? $gameScores[$x][$y] : null;
                    $scores[] = ($score != null && isset($score->responseTime) && $score->responseTime != "0") ? $score->responseTime : ".";
                }
            }

            fputcsv($fp, array_merge(array(
                $game->id,
                (empty($game->subject_id)) ? "." : $game->subject_id,
                (empty($game->session_id)) ? "." : $game->session_id,
                (empty($game->test_name)) ? "." : $game->test_name,
                (empty($game->grade)) ? "." : $game->grade,
                (empty($game->dob)) ? "." : $game->dob,
                (empty($game->age)) ? "." : $game->age,
                (empty($game->sex)) ? "." : $game->sex,
                (empty($game->played_at)) ? "." : $game->played_at,
                (empty($game->score)) ? "." : $game->score,
                (empty($game->ts_start)) ? "." : $game->ts_start,
                (empty($game->ts_lvl1_start)) ? "." : $game->ts_lvl1_start,
                (empty($game->ts_lvl1_end)) ? "." : $game->ts_lvl1_end,
                (empty($game->ts_lvl2_start)) ? "." : $game->ts_lvl2_start,
                (empty($game->ts_lvl2_end)) ? "." : $game->ts_lvl2_end,
                (empty($game->ts_lvl3_start)) ? "." : $game->ts_lvl3_start,
                (empty($game->ts_lvl3_end)) ? "." : $game->ts_lvl3_end,
                (empty($game->ts_lvl4_start)) ? "." : $game->ts_lvl4_start,
                (empty($game->ts_lvl4_end)) ? "." : $game->ts_lvl4_end,
                (empty($game->ts_lvl5_start)) ? "." : $game->ts_lvl5_start,
                (empty($game->ts_lvl5_end)) ? "." : $game->ts_lvl5_end,
                (empty($game->ts_lvl6_start)) ? "." : $game->ts_lvl6_start,
                (empty($game->ts_lvl6_end)) ? "." : $game->ts_lvl6_end,
                (empty($game->ts_lvl7_start)) ? "." : $game->ts_lvl7_start,
                (empty($game->ts_lvl7_end)) ? "." : $game->ts_lvl7_end,
                (empty($game->ts_lvl8_start)) ? "." : $game->ts_lvl8_start,
                (empty($game->ts_lvl8_end)) ? "." : $game->ts_lvl8_end,
            ), $scores));
        }

        fclose($fp);

        return View::make("csv", array(
            "filename" => $filename
        ));
    }

    public function deleteGame($game_id)
    {
        MrAntScore::where("game_id", "=", $game_id)->delete();
        MrAntGame::where("id", "=", $game_id)->delete();

        return ["success" => true];
    }
}