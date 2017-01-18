<?php

use \Illuminate\Database\Eloquent\Model;

class Games
{
    private $perms;
    private $model;

    public function __construct(Model $game)
    {
        $permData    = App::make('perms');
        $this->model = $game;
        $this->perms = array();

        foreach($permData as $perm) {
            $this->perms[] = $perm->test_name;
        }
    }

    public function getGames($test_name = null, $start = null, $end = null, $with = [])
    {
        $order = (Input::has("order")) ? Input::get("order") : "played_at";

        foreach($this->perms as $key => $val) {
            if ($val == "Untitled Test (.)") {
                $this->perms[$key] = '';
            }
        }

        if (!empty($test_name) && !empty($start) && !empty($end)) {
            if ($test_name == "all") {
                $games = $this->model
                    ->whereIn("test_name", $this->perms)
                    ->where("played_at", ">=", $start)
                    ->where("played_at", "<=", $end)
                    ->with($with)
                    ->orderBy($order, "DESC")->get();
            } else {
                if (!in_array($test_name, $this->perms)) {
                    echo View::make("alert", array(
                       "type" => "danger",
                        "msg" => "Access Denied"
                    ));
                    exit(0);
                }

                $games = $this->model
                    ->where("test_name", "=", $test_name)
                    ->where("played_at", ">=", $start)
                    ->where("played_at", "<=", $end)
                    ->with($with)
                    ->orderBy($order, "DESC")->get();
            }
        } else if (!empty($test_name) && $test_name != "all") {
            if (!in_array($test_name, $this->perms)) {
                echo View::make("alert", array(
                    "type" => "danger",
                    "msg" => "Access Denied"
                ));
                exit(0);
            }

            $games = $this->model
                ->where("test_name", "=", $test_name)
                ->with($with)
                ->orderBy($order, "DESC")->get();
        } else {
            $games = $this->model
                ->whereIn("test_name", $this->perms)
                ->with($with)
                ->orderBy($order, "DESC")->get();
        }

        return $games;
    }

    public function getEcersEntries($test_name = null, $start = null, $end = null, $with = [])
    {
        $order = (Input::has("order")) ? Input::get("order") : "start";

        if (!empty($test_name) && !empty($start) && !empty($end)) {
            if ($test_name == "all") {
                $games = $this->model
                    ->whereIn("study", $this->perms)
                    ->where("start", ">=", $start)
                    ->where("start", "<=", $end)
                    ->with($with)
                    ->orderBy($order, "DESC")->get();
            } else {
                if (!in_array($test_name, $this->perms)) {
                    echo View::make("alert", array(
                        "type" => "danger",
                        "msg" => "Access Denied"
                    ));
                    exit(0);
                }

                $games = $this->model
                    ->where("study", "=", $test_name)
                    ->where("start", ">=", $start)
                    ->where("start", "<=", $end)
                    ->with($with)
                    ->orderBy($order, "DESC")->get();
            }
        } else if (!empty($test_name) && $test_name != "all") {
            if (!in_array($test_name, $this->perms)) {
                echo View::make("alert", array(
                    "type" => "danger",
                    "msg" => "Access Denied"
                ));
                exit(0);
            }

            $games = $this->model
                ->where("study", "=", $test_name)
                ->with($with)
                ->orderBy($order, "DESC")->get();
        } else {
            $games = $this->model
                ->whereIn("study", $this->perms)
                ->with($with)
                ->orderBy($order, "DESC")->get();
        }

        return $games;
    }

    public function deleteGames(Model $scores, array $games)
    {
        if (count($games) > 0) {
            $this->model->whereIn("id", $games)->delete();

            if (get_class($scores) == "EcersData") {
                $scores::whereIn("entry_id", $games)->delete();
            } else {
                $scores::whereIn("game_id", $games)->delete();
            }
        }

        return View::make("alert", ["type"=>"success", "msg" => "Successfully deleted games"]);
    }
} 