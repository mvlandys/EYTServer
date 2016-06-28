<?php

use Illuminate\Database\Eloquent\Model;

/**
 * VocabGameNew
 *
 * @property integer $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $subject_id
 * @property string $session_id
 * @property string $test_name
 * @property string $grade
 * @property string $dob
 * @property integer $age
 * @property integer $sex
 * @property string $played_at
 * @property integer $score
 * @property-read \Illuminate\Database\Eloquent\Collection|\VocabScoreNew[] $scores
 * @method static \Illuminate\Database\Query\Builder|\VocabGameNew whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\VocabGameNew whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\VocabGameNew whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\VocabGameNew whereSubjectId($value)
 * @method static \Illuminate\Database\Query\Builder|\VocabGameNew whereSessionId($value)
 * @method static \Illuminate\Database\Query\Builder|\VocabGameNew whereTestName($value)
 * @method static \Illuminate\Database\Query\Builder|\VocabGameNew whereGrade($value)
 * @method static \Illuminate\Database\Query\Builder|\VocabGameNew whereDob($value)
 * @method static \Illuminate\Database\Query\Builder|\VocabGameNew whereAge($value)
 * @method static \Illuminate\Database\Query\Builder|\VocabGameNew whereSex($value)
 * @method static \Illuminate\Database\Query\Builder|\VocabGameNew wherePlayedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\VocabGameNew whereScore($value)
 */
class VocabGameNew extends Model
{
    protected $fillable = [];
    protected $table = "vocab_games_new";

    public function scores()
    {
        return $this->hasMany("VocabScoreNew", "game_id", "id")->orderBy("card", "ASC");
    }
}