<?php

use Illuminate\Database\Eloquent\Model;

/**
 * VocabGame
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
 * @property-read \Illuminate\Database\Eloquent\Collection|\VocabScore[] $scores
 * @method static \Illuminate\Database\Query\Builder|\VocabGame whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\VocabGame whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\VocabGame whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\VocabGame whereSubjectId($value)
 * @method static \Illuminate\Database\Query\Builder|\VocabGame whereSessionId($value)
 * @method static \Illuminate\Database\Query\Builder|\VocabGame whereTestName($value)
 * @method static \Illuminate\Database\Query\Builder|\VocabGame whereGrade($value)
 * @method static \Illuminate\Database\Query\Builder|\VocabGame whereDob($value)
 * @method static \Illuminate\Database\Query\Builder|\VocabGame whereAge($value)
 * @method static \Illuminate\Database\Query\Builder|\VocabGame whereSex($value)
 * @method static \Illuminate\Database\Query\Builder|\VocabGame wherePlayedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\VocabGame whereScore($value)
 */
class VocabGame extends Model
{
    protected $fillable = [];
    protected $table = "vocab_games";

    public function scores()
    {
        return $this->hasMany("VocabScore", "game_id", "id")->orderBy("card", "ASC");
    }
}