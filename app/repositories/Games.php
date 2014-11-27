<?php

use \Illuminate\Database\Eloquent\Model;

class Games
{
    private $perms;
    private $model;

    public function __construct(Model $game)
    {
        $user_id     = Session::get("user_id");
        $permData    = UserPermissions::where("user_id", "=", $user_id)->get(["test_name"]);
        $this->model = $game;
        $this->perms = array();

        foreach($permData as $perm) {
            $this->perms[] = $perm->test_name;
        }
    }

    public function getGames($test_name = null, $start = null, $end = null)
    {
        $order = (Input::has("order")) ? Input::get("order") : "played_at";

        if (!empty($test_name) && !empty($start) && !empty($end)) {
            if ($test_name == "all") {
                $games = $this->model
                    ->whereIn("test_name", $this->perms)
                    ->where("played_at", ">=", $start)
                    ->where("played_at", "<=", $end)
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

            $games = $this->model->where("test_name", "=", $test_name)->orderBy($order, "DESC")->get();
        } else {
            $games = $this->model->whereIn("test_name", $this->perms)->orderBy($order, "DESC")->get();
        }

        return $games;
    }
} 