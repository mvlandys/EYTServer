<?php

use Illuminate\Routing\Controller;

class QuestionnaireController extends Controller
{
    public function saveAnswers()
    {
        if (!Input::has("results")) {
            return array("error" => "No Game Data specified");
        }

        // Log game data
        Mail::send('email_log', array(), function($message) {
            $message->to("mvlandys@gmail.com")->subject("Questionnaire Log " . date("H:i:s d/m/Y"));
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

            for ($q = 1; $q < 41; $q++) {
                QuestionnaireAnswer::create(array(
                    "game_id"  => $result->id,
                    "question" => $q,
                    "answer"   => ($resultData[$q] == 0) ? "." : intval($resultData[$q])
                ));
            }
        }

        return Input::all();
    }

    public function showResults($test_name = null, $start = null, $end = null)
    {
        $results = $this->getResults($test_name, $start, $end);

        return View::make("questionnaire/results", array(
            "results"   => $results,
            "test_name" => $test_name,
            "start"     => (!empty($start)) ? DateTime::createFromFormat("Y-m-d", $start)->format("d/m/Y") : null,
            "end"       => (!empty($end)) ? DateTime::createFromFormat("Y-m-d", $end)->format("d/m/Y") : null,
            "tests"     => Questionnaire::all(array("test_name"))
        ));
    }

    private function getResults($test_name = null, $start = null, $end = null)
    {
        if (!empty($test_name) && !empty($start) && !empty($end)) {
            $results = Questionnaire::where("test_name", "=", $test_name)->where("played_at", ">=", $start)->where("played_at", "<=", $end)->get();
        } else if (!empty($test_name)) {
            $results = Questionnaire::where("test_name", "=", $test_name)->get();
        } else {
            $results = Questionnaire::all()->sortBy("played_at DESC");
        }

        return $results;
    }

    public function viewScores($game_id)
    {
        $scores = QuestionnaireAnswer::where("game_id", "=", $game_id)->orderBy("question", "ASC")->get();

        return View::make("questionnaire/scores", array(
            "scores" => $scores
        ));
    }

    public function makeCSV($test_name = null, $start = null, $end = null)
    {
        $results  = $this->getResults($test_name, $start, $end);
        $filename = date("U") . ".csv";

        $fp     = fopen(public_path() . "/tmp/" . $filename, 'w');
        $qCount = array();

        for ($x = 1; $x < 41; $x++) {
            $qCount[] = "Question_" . $x;
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
        ), $qCount));

        foreach ($results as $result) {
            $answers = array();

            foreach ($result->answers()->orderBy("question")->get() as $answer) {
                $answers[] = (isset($answer->answer)) ? $answer->answer : ".";
            }

            fputcsv($fp, array_merge(array(
                $result->id,
                (empty($result->subject_id)) ? "." : $result->subject_id,
                (empty($result->session_id)) ? "." : $result->session_id,
                (empty($result->test_name)) ? "." : $result->test_name,
                (empty($result->grade)) ? "." : $result->grade,
                (empty($result->dob)) ? "." : $result->dob,
                (empty($result->age)) ? "." : $result->age,
                (empty($result->sex)) ? "." : $result->sex,
                (empty($result->played_at)) ? "." : $result->played_at,
            ), $answers));
        }

        fclose($fp);

        return View::make("csv", array(
            "filename" => $filename
        ));
    }

    public function showForm()
    {
        return View::make("questionnaire/form", array(
            "test_name"  => (Input::has("test_name")) ? Input::get("test_name") : "",
            "subject_id" => (Input::has("subject_id")) ? Input::get("subject_id") : "",
            "session_id" => (Input::has("session_id")) ? Input::get("session_id") : "",
            "grade"      => (Input::has("grade")) ? Input::get("grade") : "",
            "dob"        => (Input::has("dob")) ? Input::get("dob") : "",
            "age"        => (Input::has("age")) ? Input::get("age") : "",
            "sex"        => (Input::has("sex")) ? Input::get("sex") : "",
            "type"       => (Input::has("type")) ? Input::get("type") : ""
        ));
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

        for ($q = 1; $q < 41; $q++) {
            QuestionnaireAnswer::create(array(
                "game_id"  => $result->id,
                "question" => $q,
                "answer"   => Input::get($q)
            ));
        }

        return View::make("alert", array(
            "msg"  => "Thank You",
            "type" => "success"
        ));
    }

    public function deleteGame($game_id)
    {
        Questionnaire::where("game_id", "=", $game_id)->delete();
        QuestionnaireAnswer::where("id", "=", $game_id)->delete();

        return ["success" => true];
    }
}