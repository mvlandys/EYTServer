<?php

use Illuminate\Database\Eloquent\Model;

/**
 * Questionnaire
 *
 * @property integer $id
 * @property string $subject_id
 * @property string $session_id
 * @property string $test_name
 * @property string $grade
 * @property string $dob
 * @property string $age
 * @property string $sex
 * @property string $played_at
 * @property string $type
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\QuestionnaireAnswer[] $answers
 * @method static \Illuminate\Database\Query\Builder|\Questionnaire whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Questionnaire whereSubjectId($value)
 * @method static \Illuminate\Database\Query\Builder|\Questionnaire whereSessionId($value)
 * @method static \Illuminate\Database\Query\Builder|\Questionnaire whereTestName($value)
 * @method static \Illuminate\Database\Query\Builder|\Questionnaire whereGrade($value)
 * @method static \Illuminate\Database\Query\Builder|\Questionnaire whereDob($value)
 * @method static \Illuminate\Database\Query\Builder|\Questionnaire whereAge($value)
 * @method static \Illuminate\Database\Query\Builder|\Questionnaire whereSex($value)
 * @method static \Illuminate\Database\Query\Builder|\Questionnaire wherePlayedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Questionnaire whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\Questionnaire whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Questionnaire whereUpdatedAt($value)
 */
class Questionnaire extends Model
{
    protected $table = "questionnaire";

    protected $fillable = [];

    public function answers()
    {
        return $this->hasMany("QuestionnaireAnswer", "game_id", "id");
    }
}