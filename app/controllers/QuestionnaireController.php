<?php

use Illuminate\Routing\Controller;

class QuestionnaireController extends Controller
{
    public function saveAnswers()
    {
        if (!Input::has("results")) {
            return ["error" => "No Game Data specified"];
        }

        // Log game data
        Mail::send('email_log', [], function($message) {
            $message->to(["mvlandys@gmail.com"])->subject("Questionnaire Log " . date("H:i:s d/m/Y"));
        });

        $results = Input::get("results");

        foreach ($results as $resultData) {
            if (empty($resultData["user_data"])) {
                continue;
            }

            $result             = new Questionnaire();
            $result->subject_id = $resultData["user_data"]["subject_id"];
            $result->session_id = $resultData["user_data"]["session_id"];
            $result->grade      = $resultData["user_data"]["grade"];
            $result->sex        = $resultData["user_data"]["sex"];
            $result->test_name  = $resultData["user_data"]["test_name"];
            $result->played_at  = $resultData["played_at"];
            $result->age        = (empty($resultData["user_data"]["age"])) ? 0 : $resultData["user_data"]["age"];
            $result->dob        = (empty($resultData["user_data"]["dob"])) ? null : \DateTime::createFromFormat("d/m/Y", $resultData["user_data"]["dob"]);
            $result->save();

            for ($q = 1; $q < 34; $q++) {
                QuestionnaireAnswer::create(["game_id"  => $result->id,
                                             "question" => $q,
                                             "answer"   => ($resultData[$q] == 0) ? "." : intval($resultData[$q])]);
            }
        }

        return Input::all();
    }

    public function showResults($test_name = null, $start = null, $end = null)
    {
        $results   = $this->getResults($test_name, $start, $end);
        $tests     = App::make('perms');
        $testNames = [];

        foreach ($tests as $test) {
            $key = str_replace("+", "%20", urlencode($test->test_name));
            if (!isset($testNames[$key])) {
                $testNames[$key] = $test;
            }
        }

        return View::make("questionnaire/results", ["results"   => $results,
                                                    "test_name" => $test_name,
                                                    "start"     => (!empty($start)) ? DateTime::createFromFormat("Y-m-d", $start)->format("d/m/Y") : null,
                                                    "end"       => (!empty($end)) ? DateTime::createFromFormat("Y-m-d", $end)->format("d/m/Y") : null,
                                                    "tests"     => $testNames]);
    }

    private function getResults($test_name = null, $start = null, $end = null)
    {
        $order = (Input::has("order")) ? Input::get("order") : "played_at";

        if (!empty($test_name) && !empty($start) && !empty($end)) {
            $results = Questionnaire::where("test_name", "=", $test_name)->where("played_at", ">=", $start)->where("played_at", "<=", $end)->orderBy($order, "DESC")->get();
        } else if (!empty($test_name)) {
            $results = Questionnaire::where("test_name", "=", $test_name)->orderBy($order, "DESC")->get();
        } else {
            $results = Questionnaire::orderBy($order, "DESC")->get();
        }

        return $results;
    }

    public function viewScores($game_id)
    {
        $scores = QuestionnaireAnswer::where("game_id", "=", $game_id)->orderBy("question", "ASC")->get();

        return View::make("questionnaire/scores", ["scores" => $scores]);
    }

    public function makeCSV($test_name = null, $start = null, $end = null)
    {
        $results  = $this->getResults($test_name, $start, $end);
        $filename = date("U") . ".csv";

        $fp     = fopen(public_path() . "/tmp/" . $filename, 'w');
        $qCount = [];

        for ($x = 1; $x < 34; $x++) {
            $qCount[] = "Question_" . $x;
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
                                  "CSBQ_Sociability",
                                  "CSBQ_External",
                                  "CSBQ_Internal",
                                  "CSQB_Prosoc",
                                  "CSBQ_BehavSR",
                                  "CSQB_CogSR",
                                  "CSBQ_EmoSR",], $qCount));

        foreach ($results as $result) {
            $answers = [];

            $scores = $this->calculateScores($result);

            foreach ($result->answers()->orderBy("question")->get() as $answer) {
                if ($answer->question < 34) {
                    $answers[] = (isset($answer->answer)) ? $answer->answer : ".";
                }
            }

            fputcsv($fp, array_merge([$result->id,
                                      (empty($result->subject_id)) ? "." : $result->subject_id,
                                      (empty($result->session_id)) ? "." : $result->session_id,
                                      (empty($result->test_name)) ? "." : $result->test_name,
                                      (empty($result->grade)) ? "." : $result->grade,
                                      (empty($result->dob)) ? "." : $result->dob,
                                      (empty($result->age)) ? "." : $result->age,
                                      (empty($result->sex)) ? "." : $result->sex,
                                      (empty($result->played_at)) ? "." : $result->played_at,
                                      $scores[1],
                                      $scores[2],
                                      $scores[3],
                                      $scores[4],
                                      $scores[5],
                                      $scores[6],
                                      $scores[7]], $answers));
        }

        fclose($fp);

        return View::make("csv", ["filename" => $filename]);
    }

    public function showForm()
    {
        return View::make("questionnaire/form", ["test_name"  => (Input::has("test_name")) ? Input::get("test_name") : "",
                                                 "subject_id" => (Input::has("subject_id")) ? Input::get("subject_id") : "",
                                                 "session_id" => (Input::has("session_id")) ? Input::get("session_id") : "",
                                                 "grade"      => (Input::has("grade")) ? Input::get("grade") : "",
                                                 "dob"        => (Input::has("dob")) ? Input::get("dob") : "",
                                                 "age"        => (Input::has("age")) ? Input::get("age") : "",
                                                 "sex"        => (Input::has("sex")) ? Input::get("sex") : "",
                                                 "type"       => (Input::has("type")) ? Input::get("type") : ""]);
    }

    public function submitForm()
    {
        $result             = new Questionnaire();
        $result->subject_id = Input::get("subject_id");
        $result->session_id = Input::get("session_id");
        $result->grade      = Input::get("grade");
        $result->sex        = Input::get("sex");
        $result->test_name  = Input::get("test_name");
        $result->played_at  = date("Y-m-d H:i:s");
        $result->age        = (Input::get("age") == "") ? 0 : Input::get("age");
        $result->dob        = (Input::get("dob") == "") ? null : \DateTime::createFromFormat("d/m/Y", Input::get("dob"))->format("Y-m-d");
        $result->save();

        for ($q = 1; $q < 35; $q++) {
            QuestionnaireAnswer::create(["game_id"  => $result->id,
                                         "question" => $q,
                                         "answer"   => Input::get($q)]);
        }

        return View::make("alert", ["msg"  => "Thank You",
                                    "type" => "success"]);
    }

    public function deleteGame($game_id)
    {
        QuestionnaireAnswer::where("game_id", "=", $game_id)->delete();
        Questionnaire::where("id", "=", $game_id)->delete();

        return ["success" => true];
    }

    public function calculateScores($result)
    {
        $items   = [];
        $answers = $result->answers()->orderBy("question")->get()->toArray();

        foreach ($answers as $answer) {
            $items[$answer["question"]] = $answer["answer"];
        }

        // Version 1: 33 Items
        // Version 2: 34 Items
        if (count($items) < 34) {
            $set1 = [$items[1],
                     $items[4],
                     $this->reverseScore($items[9]),
                     $this->reverseScore($items[16]),
                     $items[27]];
            $set2 = [$items[3],
                     $items[20],
                     $items[23],
                     $items[26],
                     $items[28]];
            $set3 = [$items[11],
                     $items[17],
                     $items[21],
                     $items[25],
                     $items[33]];
            $set4 = [$items[4],
                     $items[15],
                     $items[19],
                     $items[24],
                     $items[27],
                     $items[30],
                     $items[32]];
            $set5 = [$this->reverseScore($items[7]),
                     $items[13],
                     $this->reverseScore($items[14]),
                     $items[15],
                     $this->reverseScore($items[20]),
                     $this->reverseScore($items[29]),
                     $items[30],
                     $this->reverseScore($items[31])];
            $set6 = [$items[5],
                     $items[6],
                     $items[8],
                     $items[12],
                     $items[13],
                     $items[18]];
            $set7 = [$items[2],
                     $items[10],
                     $items[13],
                     $items[15],
                     $this->reverseScore($items[20]),
                     $this->reverseScore($items[23]),
                     $this->reverseScore($items[26])];
        } else {
            $set1 = [$items[1],
                     $items[4],
                     $items[9],
                     $this->reverseScore($items[16]),
                     $this->reverseScore($items[22]),
                     $items[27],
                     $items[32]];
            $set2 = [$items[3],
                     $items[20],
                     $items[23],
                     $items[26],
                     $items[28]];
            $set3 = [
                     $items[17],
                     $items[25],
                     $items[33],
                     $items[21],
                     $items[34]];
            $set4 = [
                     $items[15],
                     $items[19],
                     $items[24],
                     $items[27],
                     $items[30]];
            $set5 = [$this->reverseScore($items[7]),
                     $items[13],
                     $items[15],
                     $this->reverseScore($items[29]),
                     $items[30],
                     $this->reverseScore($items[31])];
            $set6 = [$items[5],
                     $items[6],
                     $items[8],
                     $items[12],
                     $items[18]];
            $set7 = [$items[2],
                     $items[10],
                     $this->reverseScore($items[11]),
                     $this->reverseScore($items[14]),
                     $this->reverseScore($items[23]),
                     $this->reverseScore($items[26])];
        }

        return [1 => array_sum($set1) / count($set1),
                2 => array_sum($set2) / count($set2),
                3 => array_sum($set3) / count($set3),
                4 => array_sum($set4) / count($set4),
                5 => array_sum($set5) / count($set5),
                6 => array_sum($set6) / count($set6),
                7 => array_sum($set7) / count($set7)];
    }

    public function reverseScore($score)
    {
        switch ($score) {
            case 1:
                $score = 5;
                break;

            case 2:
                $score = 4;
                break;

            case 4:
                $score = 2;
                break;

            case 5:
                $score = 1;
                break;
        }

        return $score;
    }

    public function deleteGames()
    {
        $games   = Input::get("game_ids");
        $gameRep = new Games(new Questionnaire());

        return $gameRep->deleteGames(new QuestionnaireAnswer(), $games);
    }
}