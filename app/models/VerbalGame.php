<?php

use Illuminate\Database\Eloquent\Model;

/**
 * VerbalGame
 *
 * @property integer $id
 * @property string $child_id
 * @property string $session_id
 * @property string $test_name
 * @property string $grade
 * @property string $dob
 * @property integer $age
 * @property integer $sex
 * @property string $played_at
 * @property integer $score
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\VerbalScore[] $scores
 * @method static \Illuminate\Database\Query\Builder|\VerbalGame whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\VerbalGame whereChildId($value)
 * @method static \Illuminate\Database\Query\Builder|\VerbalGame whereSessionId($value)
 * @method static \Illuminate\Database\Query\Builder|\VerbalGame whereTestName($value)
 * @method static \Illuminate\Database\Query\Builder|\VerbalGame whereGrade($value)
 * @method static \Illuminate\Database\Query\Builder|\VerbalGame whereDob($value)
 * @method static \Illuminate\Database\Query\Builder|\VerbalGame whereAge($value)
 * @method static \Illuminate\Database\Query\Builder|\VerbalGame whereSex($value)
 * @method static \Illuminate\Database\Query\Builder|\VerbalGame wherePlayedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\VerbalGame whereScore($value)
 * @method static \Illuminate\Database\Query\Builder|\VerbalGame whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\VerbalGame whereUpdatedAt($value)
 * @property string $centre
 * @property string $assessor
 * @method static \Illuminate\Database\Query\Builder|\VerbalGame whereCentre($value)
 * @method static \Illuminate\Database\Query\Builder|\VerbalGame whereAssessor($value)
 */
class VerbalGame extends Model
{
    protected $fillable = [];
    protected $table = "verbal_games";

    public function scores()
    {
        return $this->hasMany("VerbalScore", "game_id", "id");
    }
}