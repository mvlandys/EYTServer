<?php use Illuminate\Routing\Controller;

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
        $games     = $this->getGames($test_name, $start, $end);
        $tests     = VocabGame::all(array("test_name"))->toArray();
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

    public function makeCSV($test_name = null, $start = null, $end = null)
    {
        $games    = $this->getGames($test_name, $start, $end);
        $filename = date("U") . ".csv";

        $fp         = fopen(public_path() . "/tmp/" . $filename, 'w');
        $gamesCount = array();

        for ($x = 0; $x < 48; $x++) {
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
            $scores = array();

            for ($x = 0; $x < 48; $x++) {
                $score    = VocabScore::where("game_id", "=", $game->id)->where("card", "=", $x)->first();
                $scores[] = (isset($score->value)) ? $score->value : ".";
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

    private function getGames($test_name = null, $start = null, $end = null)
    {
        if (!empty($test_name) && !empty($start) && !empty($end)) {
            $games = ($test_name == "all")
                ? VocabGame::where("played_at", ">=", $start)->where("played_at", "<=", $end)->get()
                : VocabGame::where("test_name", "=", $test_name)->where("played_at", ">=", $start)->where("played_at", "<=", $end)->get();
        } else if (!empty($test_name) && $test_name != "all") {
            $games = VocabGame::where("test_name", "=", $test_name)->get();
        } else {
            $games = VocabGame::orderBy("played_at", "DESC")->get();
        }

        return $games;
    }

    public function deleteGame($game_id)
    {
        VocabScore::where("game_id", "=", $game_id)->delete();
        VocabGame::where("id", "=", $game_id)->delete();

        return ["success" => true];
    }
}