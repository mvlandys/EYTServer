<?php

use Illuminate\Database\Eloquent\Model;

class FishSharkGame extends Model
{
    protected $table = "fishshark_games";

    protected $fillable = ["subject_id", "session_id", "test_name", "grade", "dob", "age", "sex", "played_at",
        "animation", "blank_min", "blank_max", "ts_start", "ts_lvl1_start", "ts_lvl1_end", "ts_lvl2_start",
        "ts_lvl2_end", "ts_lvl3_start", "ts_lvl3_end"];
}