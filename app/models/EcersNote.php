<?php

use \Illuminate\Database\Eloquent\Model;

/**
 * EcersNote
 *
 * @property integer $id
 * @property integer $game_id
 * @property string $test
 * @property integer $page
 * @property string $note
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\EcersNote whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\EcersNote whereGameId($value)
 * @method static \Illuminate\Database\Query\Builder|\EcersNote whereTest($value)
 * @method static \Illuminate\Database\Query\Builder|\EcersNote wherePage($value)
 * @method static \Illuminate\Database\Query\Builder|\EcersNote whereNote($value)
 * @method static \Illuminate\Database\Query\Builder|\EcersNote whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\EcersNote whereUpdatedAt($value)
 */
class EcersNote extends Model
{
    protected $table = "ecers_notes";
}