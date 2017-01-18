<?php

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response;

class NumeracyController extends Controller
{
    public function saveEntries()
    {
        if (!Input::has("games")) {
            return Response::json(["error" => "No Game Data specified"], 400);
        }

        Log::info(json_encode(Input::all()));

        // Log game data
        Mail::send('email_log', [], function($message) {
            $message->to(["mathew@icrm.net.au"])->subject("Numeracy Log " . date("H:i:s d/m/Y"));
        });

        $games = Input::get("games");

        foreach ($games as $gameData) {
            if (empty($gameData["user_data"])) {
                continue;
            }

            $game             = new NumeracyGame();
            $game->child_id   = $gameData["user_data"]["child_id"];
            $game->session_id = $gameData["user_data"]["session_id"];
            $game->test_name  = (empty($gameData["user_data"]["test_name"])) ? "Untitled Test" : $gameData["user_data"]["test_name"];
            $game->grade      = $gameData["user_data"]["grade"];
            $game->dob        = (empty($gameData["user_data"]["dob"])) ? null : \DateTime::createFromFormat("d/m/Y", $gameData["user_data"]["dob"]);
            $game->age        = $gameData["user_data"]["age"];
            $game->sex        = $gameData["user_data"]["sex"];
            $game->assessor   = $gameData["user_data"]["assessor"];
            $game->centre     = $gameData["user_data"]["centre"];
            $game->played_at  = $gameData["played_at"];
            $game->save();

            $gameScore = 0;
            foreach ($gameData["scores"] as $score_data) {
                $key      = key($score_data);
                $value    = $score_data[$key];
                $response = "";

                if (strpos($key, "Response") !== false) {
                    $key = str_replace("Response-", "", $key);
                    $gameScore += 0;
                    $response = $value;
                    $value    = 0;
                } else {
                    $gameScore += $value;
                }

                $meta = explode("-", $key);

                $score           = new NumeracyScore();
                $score->game_id  = $game->id;
                $score->level    = $meta[0];
                $score->part     = $meta[1];
                $score->value    = $value;
                $score->response = $response;
                $score->save();
            }

            $game->score = $gameScore;
            $game->save();
        }

        return Response::json(["success"]);
    }

    public function showResults($test_name = null, $start = null, $end = null)
    {
        $gameRep   = new Games(new NumeracyGame());
        $games     = $gameRep->getGames($test_name, $start, $end);
        $tests     = App::make('perms');
        $testNames = [];

        foreach ($tests as $test) {
            $key = str_replace("+", "%20", urlencode($test->test_name));
            if (!isset($testNames[$key])) {
                $testNames[$key] = $test;
            }
        }

        return View::make("numeracy/results", ["games"     => $games,
                                               "test_name" => $test_name,
                                               "start"     => (!empty($start)) ? DateTime::createFromFormat("Y-m-d", $start)->format("d/m/Y") : null,
                                               "end"       => (!empty($end)) ? DateTime::createFromFormat("Y-m-d", $end)->format("d/m/Y") : null,
                                               "tests"     => $testNames]);
    }

    public function viewScores($game_id)
    {
        $scores = NumeracyScore::where("game_id", "=", $game_id)->get();

        $item = 1;
        foreach ($scores as $key => $score) {
            $scores[$key]["item"] = $item;
            $item++;
        }

        return View::make("numeracy/scores", ["scores" => $scores]);
    }

    public function makeCSV($test_name = null, $start = null, $end = null, $returnFile = false)
    {
        $gameRep  = new Games(new NumeracyGame());
        $games    = $gameRep->getGames($test_name, $start, $end);
        $filename = "vocab_" . date("U") . ".csv";

        $fp         = fopen(public_path() . "/tmp/" . $filename, 'w');
        $gamesCount = [];

        for ($x = 0; $x < 21; $x++) {
            $gamesCount[] = "Item_" . ($x + 1);
        }

        fputcsv($fp, array_merge(["game_id",
                                  "child_id",
                                  "centre",
                                  "assessor",
                                  "session_id",
                                  "study_name",
                                  "grade",
                                  "DOB",
                                  "age",
                                  "sex",
                                  "DOT",
                                  "score"], $gamesCount));

        foreach ($games as $game) {
            $scores = [];

            foreach ($game->scores as $score) {
                $scores[] = ($score->value == 1 || $score->value == 0) ? $score->value : 0;
            }

            for ($x = count($scores); $x < 21; $x++) {
                $scores[] = ".";
            }

            fputcsv($fp, array_merge([$game->id,
                                      (empty($game->child_id)) ? "." : $game->child_id,
                                      (empty($game->centre)) ? "." : $game->centre,
                                      (empty($game->assessor)) ? "." : $game->assessor,
                                      (empty($game->session_id)) ? "." : $game->session_id,
                                      (empty($game->test_name)) ? "." : $game->test_name,
                                      (empty($game->grade)) ? "." : $game->grade,
                                      (empty($game->dob)) ? "." : $game->dob,
                                      (empty($game->age)) ? "." : $game->age,
                                      (empty($game->sex)) ? "." : $game->sex,
                                      (empty($game->played_at)) ? "." : $game->played_at,
                                      (empty($game->score)) ? "." : $game->score], $scores));
        }

        fclose($fp);

        if ($returnFile == true) {
            return $filename;
        } else {
            return View::make("csv", ["filename" => $filename]);
        }
    }

    public function fixDuplicates()
    {
        ini_set('max_execution_time', 300); // 5 minutes

        $games   = NumeracyGame::all();
        $deleted = [];

        foreach ($games as $game) {
            if (in_array($game->id, $deleted)) {
                continue;
            }

            $duplicates = NumeracyGame::where("id", "!=", $game->id)->where("child_id", "=", $game->child_id)->where("session_id", "=", $game->session_id)->where("test_name", "=", $game->test_name)->where("played_at", "=", $game->played_at)->get();

            foreach ($duplicates as $duplicate) {
                NumeracyScore::where("game_id", "=", $duplicate->id)->delete();
                NumeracyGame::where("id", "=", $duplicate->id)->delete();

                $deleted[] = $duplicate->id;
            }
        }

        echo "Removed " . count($deleted) . " duplicates";
    }

    public function deleteGames()
    {
        $games   = Input::get("game_ids");
        $gameRep = new Games(new NumeracyGame());
        return $gameRep->deleteGames(new NumeracyScore(), $games);
    }
}