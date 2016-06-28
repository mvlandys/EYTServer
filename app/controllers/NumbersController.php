<?php

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response;

class NumbersController extends Controller
{
    public function saveEntries()
    {
        if (!Input::has("games")) {
            return Response::json(["error" => "No Game Data specified"], 400);
        }

        Log::info(json_encode(Input::all()));

        // Log game data
        Mail::send('email_log', array(), function($message) {
            $message->to(["mathew@icrm.net.au"])->subject("Verbal Log " . date("H:i:s d/m/Y"));
        });

        $games = Input::get("games");

        foreach ($games as $gameData) {
            $game             = new NumbersGame();
            $game->child_id   = $gameData["user_data"]["child_id"];
            $game->session_id = $gameData["user_data"]["session_id"];
            $game->test_name  = $gameData["user_data"]["test_name"];
            $game->grade      = $gameData["user_data"]["grade"];
            $game->dob        = $gameData["user_data"]["dob"];
            $game->age        = $gameData["user_data"]["age"];
            $game->sex        = $gameData["user_data"]["sex"];
            $game->played_at  = $gameData["played_at"];
            $game->save();

            foreach ($gameData["scores"] as $score_data) {
                $key   = key($score_data);
                $value = $score_data[$key];
                $meta  = explode("-", $key);
                
                $score          = new NumbersScore();
                $score->game_id = $game->id;
                $score->level   = $meta[0];
                $score->part    = $meta[1];
                $score->value   = $value;
                $score->save();
            }
        }

        return Response::json(["success"]);
    }

    public function showResults($test_name = null, $start = null, $end = null)
    {
        $gameRep   = new Games(new NumbersGame());
        $games     = $gameRep->getGames($test_name, $start, $end);
        $tests     = App::make('perms');
        $testNames = array();

        foreach ($tests as $test) {
            $key = str_replace("+", "%20", urlencode($test->test_name));
            if (!isset($testNames[$key])) {
                $testNames[$key] = $test;
            }
        }

        return View::make("numbers/results", array(
            "games"     => $games,
            "test_name" => $test_name,
            "start"     => (!empty($start)) ? DateTime::createFromFormat("Y-m-d", $start)->format("d/m/Y") : null,
            "end"       => (!empty($end)) ? DateTime::createFromFormat("Y-m-d", $end)->format("d/m/Y") : null,
            "tests"     => $testNames
        ));
    }

    public function viewScores($game_id)
    {
        $scores = NumbersScore::where("game_id", "=", $game_id)->get();

        return View::make("numbers/scores", array(
            "scores" => $scores
        ));
    }
}