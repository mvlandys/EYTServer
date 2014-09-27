<?php

use Illuminate\Database\Eloquent\Model;

class FishSharkGame extends Model
{
    protected $table = "fishshark_games";

    protected $fillable = ["subject_id", "session_id", "test_name", "grade", "dob", "age", "sex", "played_at", "animation", "blank_min", "blank_max"];
}