<?php

use Illuminate\Routing\Controller;

class MrAntController extends Controller
{
    public function saveAnswers()
    {
        $game = MrAntGame::create(array(
            "subject_id" => Input::get("subject_id"),
            "session_id" => Input::get("session"),
            "test_name"  => Input::get("studyName"),
            "grade"      => Input::get("grade"),
            "dob"        => Input::get("birthdate"),
            "age"        => Input::get("age"),
            "sex"        => Input::get("sex"),
            "played_at"  => Input::get("date") . ":00",
            "score"      => Input::get("score")
        ));

        if (!empty($game->id)) {
            echo "Game ID = " . $game->id . "\n\n";

            foreach (Input::get("tries") as $score) {
                MrAntScore::create(array(
                    "game_id"      => $game->id,
                    "level"        => $score["setNumber"],
                    "part"         => $score["repNumber"],
                    "value"        => $score["correct"],
                    "responseTime" => $score["responseTime"]
                ));
            }
        } else {
            print_r(Input::all());
        }
    }

    public function showResults($test_name = null, $start = null, $end = null)
    {
        $games = $this->getGames($test_name, $start, $end);

        return View::make("mrant/results", array(
            "games"     => $games,
            "test_name" => $test_name,
            "start"     => (!empty($start)) ? DateTime::createFromFormat("Y-m-d", $start)->format("d/m/Y") : null,
            "end"       => (!empty($end)) ? DateTime::createFromFormat("Y-m-d", $end)->format("d/m/Y") : null,
            "tests"     => MrAntGame::all(array("test_name"))
        ));
    }

    private function getGames($test_name = null, $start = null, $end = null)
    {
        if (!empty($test_name) && !empty($start) && !empty($end)) {
            $games = MrAntGame::where("test_name", "=", $test_name)->where("played_at", ">=", $start)->where("played_at", "<=", $end)->get();
        } else if (!empty($test_name)) {
            $games = MrAntGame::where("test_name", "=", $test_name)->get();
        } else {
            $games = MrAntGame::orderBy("played_at", "DESC")->get();
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
                $gamesCount[] = "Item" . $x . "_" . $y . "_Score";
            }
        }

        for ($x = 1; $x < 9; $x++) {
            for ($y = 1; $y < 4; $y++) {
                $gamesCount[] = "Item" . $x . "_" . $y . "_ResponseTime";
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
            "score"
        ), $gamesCount));

        foreach ($games as $game) {
            $scores = array();

            for ($x = 1; $x < 9; $x++) {
                for ($y = 1; $y < 4; $y++) {
                    $score    = MrAntScore::where("game_id", "=", $game->id)->where("level", "=", $x)->where("part", "=", $y)->first();
                    $scores[] = (isset($score->value)) ? $score->value : ".";
                }
            }

            for ($x = 1; $x < 9; $x++) {
                for ($y = 1; $y < 4; $y++) {
                    $score    = MrAntScore::where("game_id", "=", $game->id)->where("level", "=", $x)->where("part", "=", $y)->first();
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
                (empty($game->score)) ? "." : $game->score
            ), $scores));
        }

        fclose($fp);

        return View::make("csv", array(
            "filename" => $filename
        ));
    }
}