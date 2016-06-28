<?php

use Illuminate\Database\Eloquent\Model;

/**
 * NotThisGame
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
 * @property string $played_at
 * @property integer $score
 * @property string $sex
 * @property-read \Illuminate\Database\Eloquent\Collection|\NotThisScore[] $scores
 * @method static \Illuminate\Database\Query\Builder|\NotThisGame whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\NotThisGame whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\NotThisGame whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\NotThisGame whereSubjectId($value)
 * @method static \Illuminate\Database\Query\Builder|\NotThisGame whereSessionId($value)
 * @method static \Illuminate\Database\Query\Builder|\NotThisGame whereTestName($value)
 * @method static \Illuminate\Database\Query\Builder|\NotThisGame whereGrade($value)
 * @method static \Illuminate\Database\Query\Builder|\NotThisGame whereDob($value)
 * @method static \Illuminate\Database\Query\Builder|\NotThisGame whereAge($value)
 * @method static \Illuminate\Database\Query\Builder|\NotThisGame wherePlayedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\NotThisGame whereScore($value)
 * @method static \Illuminate\Database\Query\Builder|\NotThisGame whereSex($value)
 */
class NotThisGame extends Model
{
    protected $fillable = [];
    protected $table = "notthis_games";

    public function scores()
    {
        return $this->hasMany("NotThisScore", "game_id", "id");
    }
}