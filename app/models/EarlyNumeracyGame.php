<?php

use Illuminate\Database\Eloquent\Model;

/**
 * EarlyNumeracyGame
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
 * @property-read \Illuminate\Database\Eloquent\Collection|\EarlyNumeracyScore[] $scores
 * @method static \Illuminate\Database\Query\Builder|\EarlyNumeracyGame whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\EarlyNumeracyGame whereChildId($value)
 * @method static \Illuminate\Database\Query\Builder|\EarlyNumeracyGame whereSessionId($value)
 * @method static \Illuminate\Database\Query\Builder|\EarlyNumeracyGame whereTestName($value)
 * @method static \Illuminate\Database\Query\Builder|\EarlyNumeracyGame whereGrade($value)
 * @method static \Illuminate\Database\Query\Builder|\EarlyNumeracyGame whereDob($value)
 * @method static \Illuminate\Database\Query\Builder|\EarlyNumeracyGame whereAge($value)
 * @method static \Illuminate\Database\Query\Builder|\EarlyNumeracyGame whereSex($value)
 * @method static \Illuminate\Database\Query\Builder|\EarlyNumeracyGame wherePlayedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\EarlyNumeracyGame whereScore($value)
 * @method static \Illuminate\Database\Query\Builder|\EarlyNumeracyGame whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\EarlyNumeracyGame whereUpdatedAt($value)
 */
class EarlyNumeracyGame extends Model
{
    protected $fillable = [];
    protected $table = "early_numeracy_games";

    public function scores()
    {
        return $this->hasMany("EarlyNumeracyScore", "game_id", "id");
    }
}