<?php

use \Illuminate\Database\Eloquent\Model;

/**
 * User
 *
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property integer $admin
 * @property integer $delete
 * @property integer $cardsort
 * @property integer $fishshark
 * @property integer $mrant
 * @property integer $questionnaire
 * @property integer $vocab
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $email
 * @property integer $notthis
 * @property integer $ecers
 * @method static \Illuminate\Database\Query\Builder|\User whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereUsername($value)
 * @method static \Illuminate\Database\Query\Builder|\User wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereAdmin($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereDelete($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereCardsort($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereFishshark($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereMrant($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereQuestionnaire($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereVocab($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereNotthis($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereEcers($value)
 */
class User extends Model
{
    protected $fillable = [];

    protected $table = "users";
}