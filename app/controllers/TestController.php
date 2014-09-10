<?php

use Illuminate\Routing\Controller;

class TestController extends Controller {

	public function index()
    {
        $games = VocabGame::all();

        return View::make("test", array(
            "games" => $games
        ));
    }

    public function newGame()
    {
        if (!Input::has("games")) {
            return array("error" => "No Game Data specified");
        }

        $games = Input::get("games");

        foreach($games as $gameData) {
            $game = new VocabGame();
            $game->subject_id = $gameData["user_data"]["subject_id"];
            $game->session_id = $gameData["user_data"]["session_id"];
            $game->grade = $gameData["user_data"]["grade"];
            $game->sex = $gameData["user_data"]["sex"];
            $game->test_name = $gameData["user_data"]["test_name"];

            $game->age = (empty($gameData["user_data"]["age"])) ? 0 : $gameData["user_data"]["age"];
            $dob = (empty($gameData["user_data"]["dob"])) ? null : \DateTime::createFromFormat("d/m/Y",$gameData["user_data"]["dob"]);
            $game->dob = $dob;



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