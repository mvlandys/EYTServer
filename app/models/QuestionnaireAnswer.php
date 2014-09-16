<?php

use Illuminate\Database\Eloquent\Model;

class QuestionnaireAnswer extends Model
{
    protected $table = "questionnaire_answers";

    protected $fillable = ["game_id", "question", "answer"];
}