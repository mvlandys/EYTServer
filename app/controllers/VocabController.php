<?php
use Illuminate\Routing\Controller;

class VocabController extends Controller
{

    public function saveGames()
    {
        if (!Input::has("games")) {
            return array("error" => "No Game Data specified");
        }

        // Log game data
        Mail::send('email_log', array(), function ($message) {
            $message->to(["mvlandys@gmail.com"])->subject("Vocab Log " . date("H:i:s d/m/Y"));
        });

        $games = Input::get("games");

        foreach ($games as $gameData) {
            if (empty($gameData["user_data"])) {
                continue;
            }

            $game             = new VocabGame();
            $game->subject_id = $gameData["user_data"]["subject_id"];
            $game->session_id = $gameData["user_data"]["session_id"];
            $game->grade      = $gameData["user_data"]["grade"];
            $game->sex        = $gameData["user_data"]["sex"];
            $game->test_name  = $gameData["user_data"]["test_name"];
            $game->played_at  = $gameData["played_at"];
            $game->age        = (empty($gameData["user_data"]["age"])) ? 0 : $gameData["user_data"]["age"];
            $game->dob        = (empty($gameData["user_data"]["dob"])) ? null : \DateTime::createFromFormat("d/m/Y", $gameData["user_data"]["dob"]);

            $score = 0;

            foreach ($gameData["score_data"] as $gameScore) {
                $score += $gameScore;
            }

            $game->score = $score;
            $game->save();

            foreach ($gameData["score_data"] as $card => $value) {
                $card      = substr($card, 5);
                $cardScore = new VocabScore();

                $cardScore->game_id    = $game->id;
                $cardScore->card       = $card;
                $cardScore->value      = ($value == 1) ? 1 : 0;
                $cardScore->additional = ($value != 0 || $value != 1) ? $value : 0;
                $cardScore->save();
            }
        }

        return array("success");
    }

    public function showResults($test_name = null, $start = null, $end = null)
    {
        $gameRep   = new Games(new VocabGame());
        $games     = $gameRep->getGames($test_name, $start, $end);
        $user_id   = Session::get("user_id");
        $tests     = UserPermissions::where("user_id", "=", $user_id)->get(["test_name"]);
        $testNames = array();

        foreach ($tests as $test) {
            if (!isset($testNames[$test["test_name"]])) {
                $testNames[str_replace("+", "%20", urlencode($test["test_name"]))] = $test;
            }
        }

        return View::make("vocab/results", array(
            "games"     => $games,
            "test_name" => $test_name,
            "start"     => (!empty($start)) ? DateTime::createFromFormat("Y-m-d", $start)->format("d/m/Y") : null,
            "end"       => (!empty($end)) ? DateTime::createFromFormat("Y-m-d", $end)->format("d/m/Y") : null,
            "tests"     => $testNames
        ));
    }

    public function viewScores($game_id)
    {
        $scores = VocabScore::where("game_id", "=", $game_id)->orderBy("card", "ASC")->get();

        return View::make("vocab/scores", array(
            "scores" => $scores
        ));
    }

    public function makeCSV($test_name = null, $start = null, $end = null, $returnFile = false)
    {
        $gameRep   = new Games(new VocabGame());
        $games     = $gameRep->getGames($test_name, $start, $end);
        $filename = "vocab_" . date("U") . ".csv";

        $fp         = fopen(public_path() . "/tmp/" . $filename, 'w');
        $gamesCount = array();

        for ($x = 0; $x < 49; $x++) {
            $gamesCount[] = "Item" . ($x + 1) . "_Acc";
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
            $scores    = array();
            $scoreData = VocabScore::where("game_id", "=", $game->id)->orderBy("card", "ASC")->get();

            foreach ($scoreData as $score) {
                $scores[] = $score->value;
            }

            for ($x = 0; $x < 49; $x++) {
                if (empty($scores[$x])) {
                    $scores[$x] = ".";
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
        VocabScore::where("game_id", "=", $game_id)->delete();
        VocabGame::where("id", "=", $game_id)->delete();

        return ["success" => true];
    }

    public function fixDuplicates()
    {
        $games = VocabGame::all();

        // Loop through each game
        foreach ($games as $game) {
            if (empty(VocabGame::find($game->id)->id)) {
                continue;
            }

            $duplicate = VocabGame::where("id", "!=", $game->id)
                ->where("subject_id", "=", $game->subject_id)
                ->where("session_id", "=", $game->session_id)
                ->where("test_name", "=", $game->test_name)
                ->where("grade", "=", $game->grade)
                ->where("dob", "=", $game->dob)
                ->where("age", "=", $game->age)
                ->where("sex", "=", $game->sex)
                ->where("played_at", "=", $game->played_at);

            foreach ($duplicate->get() as $gameData) {
                VocabScore::where("game_id", "=", $gameData->id)->delete();
            }

            $duplicate->delete();
        }

        $games = VocabGame::where("id", ">", 400)->get();

        // Loop through each game
        foreach ($games as $game) {
            $scores = VocabScore::where("game_id", "=", $game->id)->get();

            foreach ($scores as $score) {
                if (empty(VocabScore::find($score->id)->id)) {
                    continue;
                }

                $duplicates = VocabScore::where("game_id", "=", $game->id)->where("card", "=", $score->card)->get();
                $keep       = array();
                $delete     = array();

                if (count($duplicates) == 2) {
                    foreach ($duplicates as $duplicateScore) {
                        if (empty($keep)) {
                            $keep = $duplicateScore;
                            continue;
                        }

                        $keepDate = DateTime::createFromFormat("Y-m-d H:i:s", $keep->updated_at);
                        $newDate  = DateTime::createFromFormat("Y-m-d H:i:s", $duplicateScore->updated_at);

                        if ($newDate > $keepDate) {
                            $delete = $keep;
                            $keep   = $duplicateScore;
                        }
                    }

                    if (!empty($delete->id)) {
                        $delete->delete();
                        echo "Deleted VocabScore: " . $delete->id;
                    }
                } else {
                    break;
                }
            }
        }

        echo "Done";
    }
}