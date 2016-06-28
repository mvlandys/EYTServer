<?php

use Illuminate\Database\Eloquent\Model;

/**
 * QuestionnaireAnswer
 *
 * @property integer $id
 * @property integer $game_id
 * @property integer $question
 * @property string $answer
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\QuestionnaireAnswer whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\QuestionnaireAnswer whereGameId($value)
 * @method static \Illuminate\Database\Query\Builder|\QuestionnaireAnswer whereQuestion($value)
 * @method static \Illuminate\Database\Query\Builder|\QuestionnaireAnswer whereAnswer($value)
 * @method static \Illuminate\Database\Query\Builder|\QuestionnaireAnswer whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\QuestionnaireAnswer whereUpdatedAt($value)
 */
class QuestionnaireAnswer extends Model
{
    protected $table = "questionnaire_answers";

    protected $fillable = ["game_id", "question", "answer"];
}