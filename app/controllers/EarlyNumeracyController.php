<?php

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response;

class EarlyNumeracyController extends Controller
{
    public function saveEntries()
    {
        if (!Input::has("games")) {
            return Response::json(["error" => "No Game Data specified"], 400);
        }

        Log::info(json_encode(Input::all()));

        // Log game data
        Mail::send('email_log', [], function($message) {
            $message->to(["mathew@icrm.net.au"])->subject("Early Numeracy Log " . date("H:i:s d/m/Y"));
        });

        $games = Input::get("games");

        foreach ($games as $gameData) {
            if (empty($gameData["user_data"])) {
                continue;
            }

            $game             = new EarlyNumeracyGame();
            $game->child_id   = $gameData["user_data"]["child_id"];
            $game->session_id = $gameData["user_data"]["session_id"];
            $game->test_name  = (empty($gameData["user_data"]["test_name"])) ? "Untitled Test" : $gameData["user_data"]["test_name"];
            $game->grade      = $gameData["user_data"]["grade"];
            $game->dob        = (empty($gameData["user_data"]["dob"])) ? null : \DateTime::createFromFormat("d/m/Y", $gameData["user_data"]["dob"]);
            $game->age        = $gameData["user_data"]["age"];
            $game->sex        = $gameData["user_data"]["sex"];
            $game->played_at  = $gameData["played_at"];
            $game->score      = 0;
            $game->save();

            $gameScore = 0;
            foreach ($gameData["scores"] as $score_key => $score_data) {
                $score           = new EarlyNumeracyScore();
                $score->game_id  = $game->id;
                $score->item     = $score_key;
                $score->value    = $score_data["answer"];
                $score->response = $score_data["response"];
                $score->save();
            }

            $game->score = $gameScore;
            $game->save();
        }

        return Response::json(["success"]);
    }

    public function showResults($test_name = null, $start = null, $end = null)
    {
        $gameRep   = new Games(new EarlyNumeracyGame());
        $games     = $gameRep->getGames($test_name, $start, $end);
        $tests     = App::make('perms');
        $testNames = [];

        foreach ($tests as $test) {
            $key = str_replace("+", "%20", urlencode($test->test_name));
            if (!isset($testNames[$key])) {
                $testNames[$key] = $test;
            }
        }

        return View::make("early_numeracy/results", ["games"     => $games,
                                               "test_name" => $test_name,
                                               "start"     => (!empty($start)) ? DateTime::createFromFormat("Y-m-d", $start)->format("d/m/Y") : null,
                                               "end"       => (!empty($end)) ? DateTime::createFromFormat("Y-m-d", $end)->format("d/m/Y") : null,
                                               "tests"     => $testNames]);
    }

    public function viewScores($game_id)
    {
        $scores = EarlyNumeracyScore::where("game_id", "=", $game_id)->get()->toArray();

        $scoreValues = ["OneToOneCounting-1",
                        "OneToOneCounting-2",
                        "BasicNumeral-ShortTallMan",
                        "BasicNumeral-ShortTallWoman",
                        "BasicNumeral-Cookies-1",
                        "BasicNumeral-Cookies-2",
                        "BasicNumeral-Cookies-3",
                        "BasicNumeral-HandBerries-1",
                        "BasicNumeral-HandBerries-2",
                        "BasicNumeral-BigSmallDog-1",
                        "BasicNumeral-BigSmallDog-2",
                        "BasicNumeral-AppleTrees-1",
                        "BasicNumeral-AppleTrees-2",
                        "BasicNumeral-AppleTrees-3",
                        "BasicNumeral-Children-1",
                        "BasicNumeral-Children-2",
                        "BasicNumeral-Person-1",
                        "BasicNumeral-Person-2",
                        "BasicNumeral-Children-3",
                        "BasicNumeral-Children-4",
                        "BasicNumeral-Trees-1",
                        "BasicNumeral-Trees-2",
                        "BasicNumeral-StoreLineup-1",
                        "BasicNumeral-StoreLineup-2",
                        "BasicNumeral-Shells-1",
                        "BasicNumeral-Shells-2",
                        "ImageCounting-DogsCats-1",
                        "ImageCounting-DogsCats-2",
                        "ImageCounting-DogsCats-3",
                        "ImageCounting-DogsCats-4",
                        "ImageCounting-DogsCats-5",
                        "ImageCounting-DogsCats-6",
                        "ImageCounting-DogsCats-7",
                        "ImageCounting-DogsCats-8",
                        "ImageCounting-BananaApple-1",
                        "ImageCounting-BananaApple-2",
                        "ImageCounting-BananaApple-3",
                        "ImageCounting-BananaApple-4",
                        "Subitizing-Dots-Loading",
                        "Subitizing-Dots-1",
                        "Subitizing-Dots-Loading",
                        "Subitizing-Dots-2",
                        "Subitizing-Dots-Loading",
                        "Subitizing-Dots-3",
                        "Subitizing-Dots-Loading",
                        "Subitizing-Dots-4",
                        "Subitizing-Dots-Loading",
                        "Subitizing-Dots-5",
                        "Subitizing-Dots-Loading",
                        "Subitizing-Dots-6",
                        "Comparison-Numerals-1",
                        "Comparison-Numerals-2",
                        "Comparison-Numerals-3",
                        "Comparison-Numerals-4",
                        "Comparison-Numerals-5",
                        "Comparison-Numerals-6",
                        "Number-Order-1",
                        "Number-Order-2",
                        "Number-Order-3",
                        "Number-Order-4",
                        "Number-Order-5",
                        "Number-Order-6",
                        "TwoPart-Response-1",
                        "TwoPart-Response-2",
                        "TwoPart-Response-3",
                        "TwoPart-Response-4",
                        "TwoPart-Response-5",
                        "TwoPart-Response-6",
                        "Simple-Addition-1",
                        "Simple-Addition-2",
                        "Simple-Addition-3",
                        "Simple-Addition-4",
                        "Simple-Addition-5",
                        "Simple-Addition-6",
                        "Number-Addition-1",
                        "Number-Addition-2",
                        "Number-Addition-3",
                        "Number-Addition-4",
                        "Number-Addition-5",
                        "Number-Addition-6",
                        "Spatial-Awareness-1",
                        "Spatial-Awareness-2",
                        "Spatial-Awareness-3",
                        "Spatial-Awareness-4",
                        "Spatial-Awareness-5",
                        "Spatial-Awareness-6",
                        "Spatial-Awareness-7",
                        "Spatial-Awareness-8",
                        "Spatial-Awareness-9",
                        "Spatial-Awareness-10",
                        "Spatial-Awareness-11",
                        "Spatial-Awareness-12",
                        "Spatial-Awareness-13"];
        $scoreValues = array_flip($scoreValues);

        foreach ($scores as $key => $val) {
            $a_item = explode(".", $val["item"]);
            $a_key  = trim($a_item[count($a_item) - 1]);

            $scores[$key]["item"] = $a_key;
        }

        usort($scores, function($a, $b) use ($scoreValues) {
            $a_item = explode(".", $a["item"]);
            $a_key  = trim($a_item[count($a_item) - 1]);
            $a_sort = $scoreValues[$a_key];
            $b_item = explode(".", $b["item"]);
            $b_key  = trim($b_item[count($b_item) - 1]);
            $b_sort = $scoreValues[$b_key];

            return ($a_sort > $b_sort) ? 1 : -1;
        });

        return View::make("early_numeracy/scores", ["scores" => $scores]);
    }

    public function makeCSV($test_name = null, $start = null, $end = null, $returnFile = false)
    {
        $gameRep  = new Games(new EarlyNumeracyGame());
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

        $games   = EarlyNumeracyGame::all();
        $deleted = [];

        foreach ($games as $game) {
            if (in_array($game->id, $deleted)) {
                continue;
            }

            $duplicates = EarlyNumeracyGame::where("id", "!=", $game->id)->where("child_id", "=", $game->child_id)->where("session_id", "=", $game->session_id)->where("test_name", "=", $game->test_name)->where("played_at", "=", $game->played_at)->get();

            foreach ($duplicates as $duplicate) {
                EarlyNumeracyScore::where("game_id", "=", $duplicate->id)->delete();
                EarlyNumeracyGame::where("id", "=", $duplicate->id)->delete();

                $deleted[] = $duplicate->id;
            }
        }

        echo "Removed " . count($deleted) . " duplicates";
    }

    public function deleteGames()
    {
        $games   = Input::get("game_ids");
        $gameRep = new Games(new EarlyNumeracyGame());
        return $gameRep->deleteGames(new EarlyNumeracyScore(), $games);
    }
}