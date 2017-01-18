<?php

use Illuminate\Routing\Controller;

class VocabController extends Controller
{
    public function saveGames()
    {
        if (!Input::has("games")) {
            return ["error" => "No Game Data specified"];
        }

        // Log game data
        Mail::send('email_log', [], function($message) {
            $message->to(["mathew@icrm.net.au"])->subject("Vocab Log " . date("H:i:s d/m/Y"));
        });

        $games = Input::get("games");

        foreach ($games as $gameData) {
            if (empty($gameData["user_data"])) {
                continue;
            }

            $game             = new VocabGameNew();
            $game->subject_id = $gameData["user_data"]["subject_id"];
            $game->session_id = $gameData["user_data"]["session_id"];
            $game->grade      = $gameData["user_data"]["grade"];
            $game->sex        = $gameData["user_data"]["sex"];
            $game->test_name  = (empty($gameData["user_data"]["test_name"])) ? "Untitled Test" : $gameData["user_data"]["test_name"];
            $game->played_at  = $gameData["played_at"];
            $game->age        = (empty($gameData["user_data"]["age"])) ? 0 : $gameData["user_data"]["age"];
            $game->dob        = (empty($gameData["user_data"]["dob"])) ? null : \DateTime::createFromFormat("d/m/Y", $gameData["user_data"]["dob"]);

            $score = 0;

            foreach ($gameData["score_data"] as $gameScore) {
                $score += $gameScore;
            }

            $game->score = $score;
            $game->save();

            if (count($gameData["score_data"]) > 45) {
                //$gameData["score_data"] = $this->convertOldToNew($gameData["score_data"]);
            }

            foreach ($gameData["score_data"] as $card => $value) {
                $card      = (strlen($card) > 5) ? substr($card, 5) : $card;
                $cardScore = new VocabScoreNew();

                $cardScore->game_id    = $game->id;
                $cardScore->card       = $card;
                $cardScore->value      = ($value == 1) ? 1 : 0;
                $cardScore->additional = ($value != 0 || $value != 1) ? $value : 0;
                $cardScore->save();
            }
        }

        return ["success"];
    }

    /*
    public function saveGames()
    {
        ini_set('max_execution_time', '9999999999999');

        if (!Input::has("games")) {
            return array("error" => "No Game Data specified");
        }

        // Log game data
        Mail::send('email_log', array(), function ($message) {
            $message->to(["mathew@icrm.net.au"])->subject("Vocab Log " . date("H:i:s d/m/Y"));
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
    */

    public function showResults($test_name = null, $start = null, $end = null)
    {
        $gameRep   = new Games(new VocabGameNew());
        $games     = $gameRep->getGames($test_name, $start, $end);
        $tests     = App::make('perms');
        $testNames = [];

        foreach ($tests as $test) {
            $key = str_replace("+", "%20", urlencode($test->test_name));
            if (!isset($testNames[$key])) {
                $testNames[$key] = $test;
            }
        }

        return View::make("vocab/results_new", ["games"     => $games,
                                                "test_name" => $test_name,
                                                "start"     => (!empty($start)) ? DateTime::createFromFormat("Y-m-d", $start)->format("d/m/Y") : null,
                                                "end"       => (!empty($end)) ? DateTime::createFromFormat("Y-m-d", $end)->format("d/m/Y") : null,
                                                "tests"     => $testNames]);
    }

    public function viewScores($game_id)
    {
        $scores    = VocabScoreNew::where("game_id", "=", $game_id)->orderBy("card", "ASC")->get();
        $incorrect = 0;

        foreach ($scores as $key => $score) {
            if ($score->value === 0) {
                $incorrect++;
            } else {
                $incorrect = 0;
            }

            if ($incorrect > 6 && $score->value == 0) {
                $score->value = ".";
            }

            if ($key > 54) {
                unset($scores[$key]);
            }
        }

        $cardNames = (count($scores) > 50) ? array_values($this->cardNamesNew()) : array_values($this->cardNames());

        return View::make("vocab/scores", ["scores" => $scores,
                                           "names"  => $cardNames]);
    }

    /*
    public function showResults($test_name = null, $start = null, $end = null)
    {
        $gameRep   = new Games(new VocabGame());
        $games     = $gameRep->getGames($test_name, $start, $end);
        $tests     = App::make('perms');
        $testNames = array();

        foreach ($tests as $test) {
            $key = str_replace("+", "%20", urlencode($test->test_name));
            if (!isset($testNames[$key])) {
                $testNames[$key] = $test;
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
    */

    /*
    public function viewScores($game_id)
    {
        $scores = VocabScore::where("game_id", "=", $game_id)->orderBy("card", "ASC")->get();

        return View::make("vocab/scores", array(
            "scores" => $scores,
            "names"  => array_values($this->cardNames())
        ));
    }
    */

    public function makeCSV($test_name = null, $start = null, $end = null, $returnFile = false)
    {
        $gameRep  = new Games(new VocabGameNew());
        $games    = $gameRep->getGames($test_name, $start, $end);
        $filename = "vocab_" . date("U") . ".csv";

        $fp         = fopen(public_path() . "/tmp/" . $filename, 'w');
        $gamesCount = [];
        $cardNames  = array_values($this->cardNamesNew());

        for ($x = 0; $x < 55; $x++) {
            $gamesCount[] = $cardNames[$x] . "_" . ($x + 1) . "_Acc";
        }

        fputcsv($fp, array_merge(["game_id",
                                  "subject_id",
                                  "session_id",
                                  "study_name",
                                  "grade",
                                  "DOB",
                                  "age",
                                  "sex",
                                  "DOT",
                                  "score",
                                  "Vocab_Acc"], $gamesCount));

        foreach ($games as $game) {
            $scores    = [];
            $incorrect = 0;

            $x = 0;
            foreach ($game->scores as $score) {
                if ($score->value === 0) {
                    $incorrect++;
                } else if ($incorrect <= 6) {
                    $incorrect = 0;
                }

                if ($incorrect > 6 || $incorrect > 6 && $score->value === 0) {
                    $score->value = ".";
                }

                if ($x < 55) {
                    $scores[] = $score->value;
                }
                $x++;
            }

            $game->score = 0;
            for ($x = 0; $x < 55; $x++) {
                if (!isset($scores[$x])) {
                    $scores[$x] = 0;
                }
            }

            $incorrect   = 0;
            $game->score = 0;
            for ($x = 0; $x < 55; $x++) {
                if ($scores[$x] === ".") {
                    $incorrect = 7;
                } else if ($scores[$x] === 0 || $incorrect >= 6) {
                    $incorrect++;
                } else if ($scores[$x] === 1 && $incorrect < 6) {
                    $incorrect = 0;
                }

                $scores[$x] = ($incorrect > 6) ? "." : $scores[$x];

                if ($scores[$x] === 1) {
                    $game->score++;
                }
            }

            fputcsv($fp, array_merge([$game->id,
                                      (empty($game->subject_id)) ? "." : $game->subject_id,
                                      (empty($game->session_id)) ? "." : $game->session_id,
                                      (empty($game->test_name)) ? "." : $game->test_name,
                                      (empty($game->grade)) ? "." : $game->grade,
                                      (empty($game->dob)) ? "." : $game->dob,
                                      (empty($game->age)) ? "." : $game->age,
                                      (empty($game->sex)) ? "." : $game->sex,
                                      (empty($game->played_at)) ? "." : $game->played_at,
                                      (empty($game->score)) ? "." : $game->score,
                                      "=SUM(H2:AZ2)"], $scores));
        }

        fclose($fp);

        if ($returnFile == true) {
            return $filename;
        } else {
            return View::make("csv", ["filename" => $filename]);
        }
    }

    /*
    public function makeCSV($test_name = null, $start = null, $end = null, $returnFile = false)
    {
        $gameRep  = new Games(new VocabGame());
        $games    = $gameRep->getGames($test_name, $start, $end);
        $filename = "vocab_" . date("U") . ".csv";

        $fp         = fopen(public_path() . "/tmp/" . $filename, 'w');
        $gamesCount = array();
        $cardNames  = array_values($this->cardNames());

        for ($x = 0; $x < 54; $x++) {
            $gamesCount[] = $cardNames[$x] . "_" . ($x + 1) . "_Acc";
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

            foreach ($game->scores as $score) {
                $scores[] = $score->value;
            }

            for ($x = 0; $x < 49; $x++) {
                if (empty($scores[$x])) {
                    $scores[$x] = "0";
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
    */

    public function deleteGame($game_id)
    {
        VocabScoreNew::where("game_id", "=", $game_id)->delete();
        VocabGameNew::where("id", "=", $game_id)->delete();

        return ["success" => true];
    }

    /*
    public function fixDuplicates()
    {
        ini_set('max_execution_time', 300); // 5 minutes

        $games   = VocabGame::all();
        $deleted = array();

        foreach ($games as $game) {
            if (in_array($game->id, $deleted)) {
                continue;
            }

            $duplicates = VocabGame::where("id", "!=", $game->id)
                ->where("subject_id", "=", $game->subject_id)
                ->where("session_id", "=", $game->session_id)
                ->where("test_name", "=", $game->test_name)
                ->where("played_at", "=", $game->played_at)
                ->get();

            foreach ($duplicates as $duplicate) {
                VocabScore::where("game_id", "=", $duplicate->id)->delete();
                VocabGame::where("id", "=", $duplicate->id)->delete();

                $deleted[] = $duplicate->id;
            }
        }

        echo "Removed " . count($deleted) . " duplicates";
    }
    */

    public function fixDuplicates()
    {
        ini_set('max_execution_time', 300); // 5 minutes

        $games   = VocabGameNew::all();
        $deleted = [];

        foreach ($games as $game) {
            if (in_array($game->id, $deleted)) {
                continue;
            }

            $duplicates = VocabGameNew::where("id", "!=", $game->id)->where("subject_id", "=", $game->subject_id)->where("session_id", "=", $game->session_id)->where("test_name", "=", $game->test_name)->where("played_at", "=", $game->played_at)->get();

            foreach ($duplicates as $duplicate) {
                VocabScoreNew::where("game_id", "=", $duplicate->id)->delete();
                VocabGameNew::where("id", "=", $duplicate->id)->delete();

                $deleted[] = $duplicate->id;
            }
        }

        echo "Removed " . count($deleted) . " duplicates";
    }

    private function cardNames()
    {
        return ["Flower",
                "Door",
                "Snake",
                "Rainbow",
                "Crocodile",
                "Teeth",
                "Spider",
                "Toothbrush",
                "Shell",
                "Hammer",
                "Umbrella",
                "Giraffe",
                "Bucket",
                "Triangle",
                "Guitar",
                "Tent",
                "Mountain",
                "Whale",
                "Bridge",
                "Feather",
                "Piano",
                "Hippopotamus",
                "Snow",
                "Spaghetti",
                "Trumpet",
                "Submarine",
                "River",
                "Diamond",
                "Screw",
                "Chain",
                "Chimney",
                "Parachute",
                "Cactus",
                "Violin",
                "Arrow",
                "Fountain",
                "Tweezers",
                "Flamingo",
                "Peacock",
                "Ruler",
                "Envelope",
                "Calendar",
                "Target",
                "Globe",
                "Harp",
                ""];

        return ["Banana",
                "Door",
                "Book",
                "Fish",
                "Flower",
                "Spider",
                "Kangeroo",
                "Hammer",
                "Snake",
                "Teeth",
                "Belt",
                "Crocodile",
                "Piano",
                "Whale",
                "Grapes",
                "Toothbrush",
                "Umbrella",
                "Mountain",
                "Rainbow",
                "Triangle",
                "Penguin",
                "Feather",
                "Fountain",
                "Tent",
                "Moon",
                "Shell",
                "Snow",
                "Bridge",
                "Spaghetti",
                "Bucket",
                "Chain",
                "Guitar",
                "Ruler",
                "Giraffe",
                "Envelope",
                "Hippopotamus",
                "Diamond",
                "Calendar",
                "Arrow",
                "Screw",
                "Parachute",
                "Cactus",
                "Globe",
                "Peacock",
                "Chimney",
                "River",
                "Harp",
                "Submarine",
                "Tweezers",
                "Flamingo",
                "Target",
                "Trumpet",
                "Violin",
                "Wrench"];
    }

    private function cardNamesNew()
    {
        return ["Flower",
                "Door",
                "Snake",
                "Rainbow",
                "Crocodile",
                "Teeth",
                "Spider",
                "Toothbrush",
                "Shell",
                "Hammer",
                "Umbrella",
                "Giraffe",
                "Bucket",
                "Triangle",
                "Guitar",
                "Tent",
                "Mountain",
                "Whale",
                "Bridge",
                "Feather",
                "Piano",
                "Hippopotamus",
                "Snow",
                "Spaghetti",
                "Vegetables",
                "Yawning",
                "Telescope",
                "Sneezing",
                "Trumpet",
                "Submarine",
                "Sweating.gif",
                "River",
                "Diamond",
                "Winking",
                "Screw",
                "Chain",
                "Chimney",
                "Parachute",
                "Cactus",
                "Violin",
                "Arrow",
                "Fountain",
                "Tweezers",
                "Flamingo",
                "Thermometer",
                "Hexagon",
                "Peacock",
                "Ruler",
                "Envelope",
                "Calendar",
                "Tusk.gif",
                "Target",
                "Globe",
                "Harp",
                "Shrugging"];

        return ["Flower",
                "Door",
                "Snake",
                "Rainbow",
                "Crocodile",
                "Teeth",
                "Spider",
                "Toothbrush",
                "Shell",
                "Hammer",
                "Umbrella",
                "Giraffe",
                "Bucket",
                "Triangle",
                "Guitar",
                "Tent",
                "Mountain",
                "Whale",
                "Bridge",
                "Feather",
                "Piano",
                "Hippopotamus",
                "Snow",
                "Spaghetti",
                "Trumpet",
                "Submarine",
                "River",
                "Diamond",
                "Screw",
                "Chain",
                "Chimney",
                "Parachute",
                "Cactus",
                "Violin",
                "Arrow",
                "Fountain",
                "Tweezers",
                "Flamingo",
                "Peacock",
                "Ruler",
                "Envelope",
                "Calendar",
                "Target",
                "Globe",
                "Harp",
                ""];
    }

    public function convertOldToNew(array $scoreData)
    {
        $old_cards    = array_values($this->cardNames());
        $new_cards    = $this->cardNamesNew();
        $newScoreData = [];

        foreach ($scoreData as $card => $value) {
            if (key_exists(substr($card, 5), $old_cards)) {
                $name = $old_cards[substr($card, 5)];
                if (array_search($name, $new_cards) !== false) {
                    $newScoreData[array_search($name, $new_cards)] = $value;
                }
            }
        }

        return $newScoreData;
    }

    public function migrateOldToNew($dateString)
    {
        ini_set('max_execution_time', 300); // 5 minutes

        $old_cards = array_values($this->cardNames());
        $new_cards = $this->cardNamesNew();
        $date      = DateTime::createFromFormat("Y-m-d", $dateString);

        if ($date === false) {
            throw new Exception("Date Format Incorrect");
        }

        $games = VocabGame::where("created_at", ">=", $date->format("Y-m-d"))->with("scores")->get();

        foreach ($games as $game) {
            $new             = new VocabGameNew();
            $new->subject_id = $game->subject_id;
            $new->session_id = $game->session_id;
            $new->grade      = $game->grade;
            $new->sex        = $game->sex;
            $new->test_name  = $game->test_name;
            $new->played_at  = $game->played_at;
            $new->age        = $game->age;
            $new->dob        = $game->dob;
            $new->save();

            $incorrect = 0;
            $lastScore = 0;
            $gameScore = 0;

            $cardCount = 0;
            foreach ($game->scores as $score) {
                if ($score->card == 0) {
                    $name = $old_cards[$cardCount];
                    $cardCount++;
                } else {
                    $name = $old_cards[$score->card];
                }

                if (array_search($name, $new_cards) !== false) {
                    if ($score->value == 0 && $lastScore == 0) {
                        $incorrect++;
                    } else {
                        $incorrect = 0;
                    }

                    $cardScore             = new VocabScoreNew();
                    $cardScore->game_id    = $new->id;
                    $cardScore->card       = array_search($name, $new_cards);
                    $cardScore->value      = ($incorrect < 7) ? $score->value : 0;
                    $cardScore->additional = $score->additional;
                    $cardScore->save();

                    $gameScore += ($incorrect < 7) ? 1 : 0;

                    $lastScore = $score->score;
                }
            }

            $new->score = $gameScore;
            $new->save();
        }

        echo "Done - " . VocabGameNew::all()->count() . "Processed" . PHP_EOL;
    }

    public function getNewCardValue()
    {
    }

    public function deleteGames()
    {
        $games   = Input::get("game_ids");
        $gameRep = new Games(new VocabGameNew());
        return $gameRep->deleteGames(new VocabScoreNew(), $games);
    }
}