<?php

use \Illuminate\Database\Eloquent\Model;

/**
 * EcersAppNote
 *
 * @property integer $id
 * @property integer $game_id
 * @property string $note
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\EcersAppNote whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\EcersAppNote whereGameId($value)
 * @method static \Illuminate\Database\Query\Builder|\EcersAppNote whereNote($value)
 * @method static \Illuminate\Database\Query\Builder|\EcersAppNote whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\EcersAppNote whereUpdatedAt($value)
 */
class EcersAppNote extends Model
{
    protected $table = "ecers_app_notes";
}