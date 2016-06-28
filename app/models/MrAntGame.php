<?php

use Illuminate\Database\Eloquent\Model;

/**
 * MrAntGame
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
 * @property string $score
 * @property string $ts_start
 * @property string $ts_lvl1_start
 * @property string $ts_lvl1_end
 * @property string $ts_lvl2_start
 * @property string $ts_lvl2_end
 * @property string $ts_lvl3_start
 * @property string $ts_lvl3_end
 * @property string $ts_lvl4_start
 * @property string $ts_lvl4_end
 * @property string $ts_lvl5_start
 * @property string $ts_lvl5_end
 * @property string $ts_lvl6_start
 * @property string $ts_lvl6_end
 * @property string $ts_lvl7_start
 * @property string $ts_lvl7_end
 * @property string $ts_lvl8_start
 * @property string $ts_lvl8_end
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\MrAntScore[] $scores
 * @method static \Illuminate\Database\Query\Builder|\MrAntGame whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\MrAntGame whereSubjectId($value)
 * @method static \Illuminate\Database\Query\Builder|\MrAntGame whereSessionId($value)
 * @method static \Illuminate\Database\Query\Builder|\MrAntGame whereTestName($value)
 * @method static \Illuminate\Database\Query\Builder|\MrAntGame whereGrade($value)
 * @method static \Illuminate\Database\Query\Builder|\MrAntGame whereDob($value)
 * @method static \Illuminate\Database\Query\Builder|\MrAntGame whereAge($value)
 * @method static \Illuminate\Database\Query\Builder|\MrAntGame whereSex($value)
 * @method static \Illuminate\Database\Query\Builder|\MrAntGame wherePlayedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\MrAntGame whereScore($value)
 * @method static \Illuminate\Database\Query\Builder|\MrAntGame whereTsStart($value)
 * @method static \Illuminate\Database\Query\Builder|\MrAntGame whereTsLvl1Start($value)
 * @method static \Illuminate\Database\Query\Builder|\MrAntGame whereTsLvl1End($value)
 * @method static \Illuminate\Database\Query\Builder|\MrAntGame whereTsLvl2Start($value)
 * @method static \Illuminate\Database\Query\Builder|\MrAntGame whereTsLvl2End($value)
 * @method static \Illuminate\Database\Query\Builder|\MrAntGame whereTsLvl3Start($value)
 * @method static \Illuminate\Database\Query\Builder|\MrAntGame whereTsLvl3End($value)
 * @method static \Illuminate\Database\Query\Builder|\MrAntGame whereTsLvl4Start($value)
 * @method static \Illuminate\Database\Query\Builder|\MrAntGame whereTsLvl4End($value)
 * @method static \Illuminate\Database\Query\Builder|\MrAntGame whereTsLvl5Start($value)
 * @method static \Illuminate\Database\Query\Builder|\MrAntGame whereTsLvl5End($value)
 * @method static \Illuminate\Database\Query\Builder|\MrAntGame whereTsLvl6Start($value)
 * @method static \Illuminate\Database\Query\Builder|\MrAntGame whereTsLvl6End($value)
 * @method static \Illuminate\Database\Query\Builder|\MrAntGame whereTsLvl7Start($value)
 * @method static \Illuminate\Database\Query\Builder|\MrAntGame whereTsLvl7End($value)
 * @method static \Illuminate\Database\Query\Builder|\MrAntGame whereTsLvl8Start($value)
 * @method static \Illuminate\Database\Query\Builder|\MrAntGame whereTsLvl8End($value)
 * @method static \Illuminate\Database\Query\Builder|\MrAntGame whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\MrAntGame whereUpdatedAt($value)
 */
class MrAntGame extends Model
{
    protected $table = "mrant_games";
    protected $fillable = ["subject_id", "session_id", "test_name", "grade", "dob", "age", "sex", "played_at",
        "ts_start", "ts_lvl1_start", "ts_lvl1_end", "ts_lvl2_start",
        "ts_lvl2_end", "ts_lvl3_start", "ts_lvl3_end", "ts_lvl4_start", "ts_lvl4_end", "ts_lvl5_start", "ts_lvl5_end"
        , "ts_lvl6_start", "ts_lvl6_end", "ts_lvl7_start", "ts_lvl7_end", "ts_lvl8_start", "ts_lvl8_end"
    ];

    public function scores()
    {
        return $this->hasMany("MrAntScore", "game_id", "id");
    }
}