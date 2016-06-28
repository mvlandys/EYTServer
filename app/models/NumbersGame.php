<?php

use Illuminate\Database\Eloquent\Model;

/**
 * NumeracyGame
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
 * @property-read \Illuminate\Database\Eloquent\Collection|\NumeracyScore[] $scores 
 * @method static \Illuminate\Database\Query\Builder|\NumeracyGame whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\NumeracyGame whereChildId($value)
 * @method static \Illuminate\Database\Query\Builder|\NumeracyGame whereSessionId($value)
 * @method static \Illuminate\Database\Query\Builder|\NumeracyGame whereTestName($value)
 * @method static \Illuminate\Database\Query\Builder|\NumeracyGame whereGrade($value)
 * @method static \Illuminate\Database\Query\Builder|\NumeracyGame whereDob($value)
 * @method static \Illuminate\Database\Query\Builder|\NumeracyGame whereAge($value)
 * @method static \Illuminate\Database\Query\Builder|\NumeracyGame whereSex($value)
 * @method static \Illuminate\Database\Query\Builder|\NumeracyGame wherePlayedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\NumeracyGame whereScore($value)
 * @method static \Illuminate\Database\Query\Builder|\NumeracyGame whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\NumeracyGame whereUpdatedAt($value)
 */
class NumbersGame extends Model
{
    protected $fillable = [];
    protected $table = "numbers_games";

    public function scores()
    {
        return $this->hasMany("NumbersScore", "game_id", "id");
    }
}