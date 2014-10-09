<?php

use Illuminate\Routing\Controller;

class FishSharkController extends Controller
{
    public function saveGames()
    {
        $dob = DateTime::createFromFormat("d-m-Y", Input::get("birthdate"));

        $game = FishSharkGame::create(array(
            "subject_id"    => Input::get("subject_id"),
            "session_id"    => Input::get("session"),
            "test_name"     => Input::get("studyName"),
            "grade"         => Input::get("grade"),
            "dob"           => (!$dob) ? "" : $dob->format("Y-m-d"),
            "age"           => Input::get("age"),
            "sex"           => Input::get("sex"),
            "played_at"     => Input::get("date") . ":00",
            "animation"     => Input::get("animation"),
            "blank_min"     => Input::get("blank_min"),
            "blank_max"     => Input::get("blank_max"),
            "ts_start"      => (empty(Input::get("timestamps")["Start"])) ? null : date("Y-m-d H:i:s", strtotime(Input::get("timestamps")["Start"])),
            "ts_lvl1_start" => (empty(Input::get("timestamps")["Level 1 Start"])) ? null : date("Y-m-d H:i:s", strtotime(Input::get("timestamps")["Level 1 Start"])),
            "ts_lvl1_end"   => (empty(Input::get("timestamps")["Level 1 End"])) ? null : date("Y-m-d H:i:s", strtotime(Input::get("timestamps")["Level 1 End"])),
            "ts_lvl2_start" => (empty(Input::get("timestamps")["Level 2 Start"])) ? null : date("Y-m-d H:i:s", strtotime(Input::get("timestamps")["Level 2 Start"])),
            "ts_lvl2_end"   => (empty(Input::get("timestamps")["Level 2 End"])) ? null : date("Y-m-d H:i:s", strtotime(Input::get("timestamps")["Level 2 End"])),
            "ts_lvl3_start" => (empty(Input::get("timestamps")["Level 3 Start"])) ? null : date("Y-m-d H:i:s", strtotime(Input::get("timestamps")["Level 3 Start"])),
            "ts_lvl3_end"   => (empty(Input::get("timestamps")["Level 3 End"])) ? null : date("Y-m-d H:i:s", strtotime(Input::get("timestamps")["Level 3 End"]))
        ));

        if (!empty($game->id)) {
            echo "Game ID = " . $game->id . "\n\n";

            foreach (Input::get("tries") as $score) {
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
        } else {
            print_r(Input::all());
        }
    }

    public function showResults($test_name = null, $start = null, $end = null)
    {
        $games     = $this->getGames($test_name, $start, $end);
        $tests     = FishSharkGame::all(array("test_name"))->toArray();
        $testNames = array();

        foreach ($tests as $test) {
            if (!isset($testNames[$test["test_name"]])) {
                $testNames[$test["test_name"]] = $test;
            }
        }

        return View::make("fishshark/results", array(
            "games"     => $games,
            "test_name" => $test_name,
            "start"     => (!empty($start)) ? DateTime::createFromFormat("Y-m-d", $start)->format("d/m/Y") : null,
            "end"       => (!empty($end)) ? DateTime::createFromFormat("Y-m-d", $end)->format("d/m/Y") : null,
            "tests"     => $tests
        ));
    }

    public function viewScores($game_id)
    {
        $scores = FishSharkScore::where("game_id", "=", $game_id)->orderBy("level", "ASC")->get();

        return View::make("fishshark/scores", array(
            "scores" => $scores
        ));
    }

    public function makeCSV($test_name = null, $start = null, $end = null)
    {
        $games    = $this->getGames($test_name, $start, $end);
        $filename = date("U") . ".csv";

        $fp         = fopen(public_path() . "/tmp/" . $filename, 'w');
        $gamesCount = array();

        $game_id = $games[0]->id;
        $fishes  = FishSharkScore::where("game_id", "=", $game_id)->where("is_shark", "=", "0")->orderBy("level", "ASC")->orderBy("part", "ASC")->get();

        $part = 1;
        foreach ($fishes as $fish) {
            if ($fish->level > 3) {
                $gamesCount[] = "GO" . ($fish->level - 3) . "_" . $part . "_Acc";
                $part++;
            }
        }

        $sharks = FishSharkScore::where("game_id", "=", $game_id)->where("is_shark", "=", "1")->orderBy("level", "ASC")->orderBy("part", "ASC")->get();
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

            $fishes = FishSharkScore::where("game_id", "=", $game->id)->where("is_shark", "=", "0")->orderBy("level", "ASC")->orderBy("part", "ASC")->get();
            $sharks = FishSharkScore::where("game_id", "=", $game->id)->where("is_shark", "=", "1")->orderBy("level", "ASC")->orderBy("part", "ASC")->get();

            // Fish Accuracy
            foreach ($fishes as $score) {
                if ($score->level > 3) {
                    $scores[] = (isset($score->value)) ? $score->value : ".";
                }
            }

            // Shark Accuracy
            foreach ($sharks as $score) {
                if ($score->level > 3) {
                    $scores[] = (isset($score->value)) ? $score->value : ".";
                }
            }

            // Fish Response
            foreach ($fishes as $score) {
                if ($score->level > 3) {
                    $scores[] = (isset($score->responseTime)) ? $score->responseTime : ".";
                }
            }

            // Shark Response
            foreach ($sharks as $score) {
                if ($score->level > 3) {
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

        return View::make("csv", array(
            "filename" => $filename
        ));
    }

    private function getGames($test_name = null, $start = null, $end = null)
    {
        if (!empty($test_name) && !empty($start) && !empty($end)) {
            $games = FishSharkGame::where("test_name", "=", $test_name)->where("played_at", ">=", $start)->where("played_at", "<=", $end)->get();
        } else if (!empty($test_name)) {
            $games = FishSharkGame::where("test_name", "=", $test_name)->get();
        } else {
            $games = FishSharkGame::orderBy("played_at", "DESC")->get();
        }

        return $games;
    }

    public function deleteGame($game_id)
    {
        FishSharkScore::where("game_id", "=", $game_id)->delete();
        FishSharkGame::where("id", "=", $game_id)->delete();

        return ["success" => true];
    }
}