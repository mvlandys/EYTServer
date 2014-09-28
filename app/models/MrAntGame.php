<?php

use Illuminate\Database\Eloquent\Model;

class MrAntGame extends Model
{
    protected $table = "mrant_games";

    protected $fillable = ["subject_id", "session_id", "test_name", "grade", "dob", "age", "sex", "played_at",
        "ts_start", "ts_lvl1_start", "ts_lvl1_end", "ts_lvl2_start",
        "ts_lvl2_end", "ts_lvl3_start", "ts_lvl3_end", "ts_lvl4_start", "ts_lvl4_end", "ts_lvl5_start", "ts_lvl5_end"
        , "ts_lvl6_start", "ts_lvl6_end", "ts_lvl7_start", "ts_lvl7_end", "ts_lvl8_start", "ts_lvl8_end"];
}