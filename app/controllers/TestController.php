<?php

use Illuminate\Routing\Controller;

class TestController extends Controller {

	public function index()
    {
        if (!Input::has("games")) {
            return array("error" => "No Game Data specified");
        }
        
        $games = Input::get("games");
        $json  = array();

        foreach($games as $game) {
            $json[] = $game;
        }

        return Response::json($json);
    }

    public function newGame()
    {
        $games = Input::get("games");

        foreach($games as $gameData) {
            $game = new VocabGame();
            $game->subject_id = $gameData["user_data"]["subject_id"];
            $game->session_id = $gameData["user_data"]["session_id"];
            $game->grade = $gameData["user_data"]["grade"];

            $dob = (empty($gameData["user_data"]["dob"])) ? null : \DateTime::createFromFormat("d/m/Y",$gameData["user_data"]["dob"]);
            $game->dob = $dob;

            $game->age = (empty($gameData["user_data"]["age"])) ? 0 : $gameData["user_data"]["age"];
            $game->sex = $gameData["user_data"]["sex"];

            $score = 0;

            foreach($gameData["score_data"] as $gameScore) {
                $score += $gameScore;
            }

            $game->score = $score;
            $game->save();
        }

        return Input::all();
    }

}