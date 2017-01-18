<?php

use \Illuminate\Database\Eloquent\Model;

/**
 * EcersQuestion
 *
 * @property integer $id 
 * @property integer $game_id 
 * @property string $study 
 * @property string $item 
 * @property string $question 
 * @property string $answer 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 * @method static \Illuminate\Database\Query\Builder|\EcersQuestion whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\EcersQuestion whereGameId($value)
 * @method static \Illuminate\Database\Query\Builder|\EcersQuestion whereStudy($value)
 * @method static \Illuminate\Database\Query\Builder|\EcersQuestion whereItem($value)
 * @method static \Illuminate\Database\Query\Builder|\EcersQuestion whereQuestion($value)
 * @method static \Illuminate\Database\Query\Builder|\EcersQuestion whereAnswer($value)
 * @method static \Illuminate\Database\Query\Builder|\EcersQuestion whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\EcersQuestion whereUpdatedAt($value)
 */
class EcersQuestion extends Model {

	protected $fillable = [];

	protected $table = "ecers_questions";
}