<?php

use Illuminate\Database\Eloquent\Model;

class Questionnaire extends Model
{
    protected $table = "questionnaire";

    protected $fillable = [];

    public function answers()
    {
        return $this->hasMany("QuestionnaireAnswer", "game_id", "id");
    }
}