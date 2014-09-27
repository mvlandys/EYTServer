<?php

use Illuminate\Database\Eloquent\Model;

class MrAntGame extends Model
{
    protected $table = "mrant_games";

    protected $fillable = ["subject_id", "session_id", "test_name", "grade", "dob", "age", "sex", "played_at"];
}