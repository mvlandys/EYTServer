<?php

use Illuminate\Routing\Controller;

class FishSharkController extends Controller
{
    public function saveGames()
    {
        $dob = DateTime::createFromFormat("d-m-Y", Input::get("birthdate"));

        $game = FishSharkGame::create(array(
            "subject_id" => Input::get("subject_id"),
            "session_id" => Input::get("session"),
            "test_name"  => Input::get("studyName"),
            "grade"      => Input::get("grade"),
            "dob"        => (!$dob) ? "" : $dob->format("Y-m-d"),
            "age"        => Input::get("age"),
            "sex"        => Input::get("sex"),
            "played_at"  => Input::get("date") . ":00",
            "animation"  => Input::get("animation"),
            "blank_min"  => Input::get("blank_min"),
            "blank_max"  => Input::get("blank_max"),
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
                    "blankTime"    => $score["blankTime"]
                ));
            }
        } else {
            print_r(Input::all());
        }
    }

    public function showResults($test_name = null, $start = null, $end = null)
    {
        $games = $this->getGames($test_name, $start, $end);

        return View::make("fishshark/results", array(
            "games"     => $games,
            "test_name" => $test_name,
            "start"     => (!empty($start)) ? DateTime::createFromFormat("Y-m-d", $start)->format("d/m/Y") : null,
            "end"       => (!empty($end)) ? DateTime::createFromFormat("Y-m-d", $end)->format("d/m/Y") : null,
            "tests"     => FishSharkGame::all(array("test_name"))
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
        $scores  = FishSharkScore::where("game_id", "=", $game_id)->orderBy("level", "ASC")->orderBy("part", "ASC")->get();

        foreach ($scores as $score) {
            $gamesCount[] = "Item" . $score->level . "_" . $score->part . "_Acc";
        }

        foreach ($scores as $score) {
            $gamesCount[] = "Item" . $score->level . "_" . $score->part . "_Resp";
        }

        foreach ($scores as $score) {
            $gamesCount[] = "Item" . $score->level . "_" . $score->part . "_Blank";
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
            "animation",
            "blank_min",
            "blank_max"
        ), $gamesCount));

        foreach ($games as $game) {
            $scores     = array();
            $gameScores = FishSharkScore::where("game_id", "=", $game->id)->orderBy("level", "ASC")->orderBy("part", "ASC")->get();

            foreach ($gameScores as $score) {
                $scores[] = (isset($score->value)) ? $score->value : ".";
            }

            foreach ($gameScores as $score) {
                $scores[] = (isset($score->responseTime)) ? $score->responseTime : ".";
            }

            foreach ($gameScores as $score) {
                $scores[] = (isset($score->blankTime)) ? $score->blankTime : ".";
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
                (empty($game->played_at)) ? "." : $game->played_at/*,
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
}