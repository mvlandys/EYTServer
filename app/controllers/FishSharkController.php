<?php

class FishSharkController extends BaseController
{
    /**
     * TEMP CODE
     *
     * Used for importing data from another database
     *
     * @return array
     */
    public function importGames()
    {
        $results = array();
        $games = Input::all();

        foreach ($games as $gameData) {
            $dob = DateTime::createFromFormat("d-m-Y", (Input::has("birthdate")) ? Input::get("birthdate") : $gameData["birthdate"]);

            $game                = new FishSharkGame();
            $game->subject_id    = $gameData["subject_id"];
            $game->session_id    = $gameData["session"];
            $game->test_name     = $gameData["studyName"];
            $game->grade         = $gameData["grade"];
            $game->dob           = (!$dob) ? "" : $dob->format("Y-m-d");
            $game->age           = $gameData["age"];
            $game->sex           = $gameData["sex"];
            $game->played_at     = $gameData["date"] . ":00";
            $game->animation     = $gameData["animation"];
            $game->blank_min     = $gameData["blank_min"];
            $game->blank_max     = $gameData["blank_max"];
            $game->ts_start      = (empty($gameData["timestamps"]["Start"])) ? null : date("Y-m-d H:i:s", strtotime($gameData["timestamps"]["Start"]));
            $game->ts_lvl1_start = (empty($gameData["timestamps"]["Level 1 Start"])) ? null : date("Y-m-d H:i:s", strtotime($gameData["timestamps"]["Level 1 Start"]));
            $game->ts_lvl1_end   = (empty($gameData["timestamps"]["Level 1 End"])) ? null : date("Y-m-d H:i:s", strtotime($gameData["timestamps"]["Level 1 End"]));
            $game->ts_lvl2_start = (empty($gameData["timestamps"]["Level 2 Start"])) ? null : date("Y-m-d H:i:s", strtotime($gameData["timestamps"]["Level 2 Start"]));
            $game->ts_lvl2_end   = (empty($gameData["timestamps"]["Level 2 End"])) ? null : date("Y-m-d H:i:s", strtotime($gameData["timestamps"]["Level 2 End"]));
            $game->ts_lvl3_start = (empty($gameData["timestamps"]["Level 3 Start"])) ? null : date("Y-m-d H:i:s", strtotime($gameData["timestamps"]["Level 3 Start"]));
            $game->ts_lvl3_end   = (empty($gameData["timestamps"]["Level 3 End"])) ? null : date("Y-m-d H:i:s", strtotime($gameData["timestamps"]["Level 3 End"]));
            $game->save();

            foreach ($gameData["tries"] as $score) {
                FishSharkScore::create(array(
                    "game_id"      => $game->id,
                    "level"        => $score["setNumber"],
                    "part"         => $score["repNumber"],
                    "value"        => $score["correct"],
                    "responseTime" => $score["responseTime"],
                    "blankTime"    => $score["blankTime"],
                    "is_shark"     => $score["isShark"]
                ));
            }

            $results[] = $game->id;
        }

        return $results;
    }

    public function saveGames()
    {
        if (!Input::has("games")) {
            return array("error" => "No Game Data specified");
        }

        // Log game data
        Mail::send('email_log', array(), function ($message) {
            $message->to(["mathew@icrm.net.au"])->subject("FishShark Log " . date("H:i:s d/m/Y"));
        });

        $games = Input::get("games");

        foreach ($games as $gameData) {
            $dob  = DateTime::createFromFormat("d-m-Y", (Input::has("birthdate")) ? Input::get("birthdate") : $gameData["birthdate"]);

            $game = FishSharkGame::create(array(
                "subject_id"    => $gameData["subject_id"],
                "session_id"    => $gameData["session"],
                "test_name"     => $gameData["studyName"],
                "grade"         => $gameData["grade"],
                "dob"           => (!$dob) ? "" : $dob->format("Y-m-d"),
                "age"           => $gameData["age"],
                "sex"           => $gameData["sex"],
                "played_at"     => $gameData["date"] . ":00",
                "animation"     => $gameData["animation"],
                "blank_min"     => $gameData["blank_min"],
                "blank_max"     => $gameData["blank_max"],
                "ts_start"      => (empty($gameData["timestamps"]["Start"])) ? null : date("Y-m-d H:i:s", strtotime($gameData["timestamps"]["Start"])),
                "ts_lvl1_start" => (empty($gameData["timestamps"]["Level 1 Start"])) ? null : date("Y-m-d H:i:s", strtotime($gameData["timestamps"]["Level 1 Start"])),
                "ts_lvl1_end"   => (empty($gameData["timestamps"]["Level 1 End"])) ? null : date("Y-m-d H:i:s", strtotime($gameData["timestamps"]["Level 1 End"])),
                "ts_lvl2_start" => (empty($gameData["timestamps"]["Level 2 Start"])) ? null : date("Y-m-d H:i:s", strtotime($gameData["timestamps"]["Level 2 Start"])),
                "ts_lvl2_end"   => (empty($gameData["timestamps"]["Level 2 End"])) ? null : date("Y-m-d H:i:s", strtotime($gameData["timestamps"]["Level 2 End"])),
                "ts_lvl3_start" => (empty($gameData["timestamps"]["Level 3 Start"])) ? null : date("Y-m-d H:i:s", strtotime($gameData["timestamps"]["Level 3 Start"])),
                "ts_lvl3_end"   => (empty($gameData["timestamps"]["Level 3 End"])) ? null : date("Y-m-d H:i:s", strtotime($gameData["timestamps"]["Level 3 End"]))
            ));

            foreach ($gameData["tries"] as $score) {
                FishSharkScore::create(array(
                    "game_id"      => $game->id,
                    "level"        => $score["setNumber"],
                    "part"         => $score["repNumber"],
                    "value"        => $score["correct"],
                    "responseTime" => $score["responseTime"],
                    "blankTime"    => $score["blankTime"],
                    "is_shark"     => $score["isShark"]
                ));
            }
        }

        return array("success");
    }

    public function showResults($test_name = null, $start = null, $end = null)
    {
        $gameRep   = new Games(new FishSharkGame());
        $games     = $gameRep->getGames($test_name, $start, $end);
        $tests     = App::make('perms');
        $testNames = array();

        foreach ($tests as $test) {
            $key = str_replace("+", "%20", urlencode($test->test_name));
            if (!isset($testNames[$key])) {
                $testNames[$key] = $test;
            }
        }

        return View::make("fishshark/results", array(
            "games"     => $games,
            "test_name" => $test_name,
            "start"     => (!empty($start)) ? DateTime::createFromFormat("Y-m-d", $start)->format("d/m/Y") : null,
            "end"       => (!empty($end)) ? DateTime::createFromFormat("Y-m-d", $end)->format("d/m/Y") : null,
            "tests"     => $testNames
        ));
    }

    public function viewScores($game_id)
    {
        $scores = FishSharkScore::where("game_id", "=", $game_id)->orderBy("level", "ASC")->get();

        return View::make("fishshark/scores", array(
            "scores" => $scores
        ));
    }

    public function makeCSV($test_name = null, $start = null, $end = null, $returnFile = false)
    {
        $gameRep   = new Games(new FishSharkGame());
        $games     = $gameRep->getGames($test_name, $start, $end, "scores");
        $filename = "fishshark_" . date("U") . ".csv";

        $fp         = fopen(public_path() . "/tmp/" . $filename, 'w');
        $gamesCount = array();

        if (count($games) > 0) {
            $fishes = array();
            foreach ($games[0]->scores as $score) {
                if ($score->is_shark == 0) {
                    $fishes[] = $score;
                }
            }

            $part = 1;
            foreach ($fishes as $fish) {
                if ($fish->level > 3) {
                    $gamesCount[] = "GO" . ($fish->level - 3) . "_" . $part . "_Acc";
                    $part++;
                }
            }

            $sharks = array();
            foreach ($games[0]->scores as $score) {
                if ($score->is_shark == 1) {
                    $sharks[] = $score;
                }
            }
            $part   = 1;
            foreach ($sharks as $shark) {
                if ($shark->level > 3) {
                    $gamesCount[] = "NG" . ($shark->level - 3) . "_" . $part . "_Acc";
                    $part++;
                }
            }

            $part = 1;
            foreach ($fishes as $fish) {
                if ($fish->level > 3) {
                    $gamesCount[] = "GO" . ($fish->level - 3) . "_" . $part . "_RT";
                    $part++;
                }
            }

            $part = 1;
            foreach ($sharks as $shark) {
                if ($shark->level > 3) {
                    $gamesCount[] = "NG" . ($shark->level - 3) . "_" . $part . "_RT";
                    $part++;
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
                "TS_Start",
                "TS_Lvl1_Start",
                "TS_Lvl1_End",
                "TS_Lvl2_Start",
                "TS_Lvl2_End",
                "TS_Lvl3_Start",
                "TS_Lvl3_End"
                /*,
                "animation",
                "blank_min",
                "blank_max"*/
            ), $gamesCount));

            foreach ($games as $game) {
                $scores = array();

                // Fish Accuracy
                foreach ($game->scores as $score) {
                    if ($score->level > 3 && $score->is_shark == 0) {
                        $scores[] = (isset($score->value)) ? $score->value : ".";
                    }
                }

                // Shark Accuracy
                foreach ($game->scores as $score) {
                    if ($score->level > 3 && $score->is_shark == 1) {
                        $scores[] = (isset($score->value)) ? $score->value : ".";
                    }
                }

                // Fish Response
                foreach ($game->scores as $score) {
                    if ($score->level > 3 && $score->is_shark == 0) {
                        $scores[] = (isset($score->responseTime)) ? $score->responseTime : ".";
                    }
                }

                // Shark Response
                foreach ($game->scores as $score) {
                    if ($score->level > 3 && $score->is_shark == 1) {
                        $scores[] = (isset($score->responseTime)) ? $score->responseTime : ".";
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
                    (empty($game->ts_start)) ? "." : $game->ts_start,
                    (empty($game->ts_lvl1_start)) ? "." : $game->ts_lvl1_start,
                    (empty($game->ts_lvl1_end)) ? "." : $game->ts_lvl1_end,
                    (empty($game->ts_lvl2_start)) ? "." : $game->ts_lvl2_start,
                    (empty($game->ts_lvl2_end)) ? "." : $game->ts_lvl2_end,
                    (empty($game->ts_lvl3_start)) ? "." : $game->ts_lvl3_start,
                    (empty($game->ts_lvl3_end)) ? "." : $game->ts_lvl3_end,
                    /*,
                    (empty($game->animation)) ? "." : $game->animation
                    (empty($game->blank_min)) ? "." : $game->blank_min
                    (empty($game->blank_max)) ? "." : $game->blank_max*/
                ), $scores));
            }

            fclose($fp);
        }

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
        FishSharkScore::where("game_id", "=", $game_id)->delete();
        FishSharkGame::where("id", "=", $game_id)->delete();

        return ["success" => true];
    }

    public function fixDuplicates()
    {
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
    }
}