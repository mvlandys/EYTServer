<?php

/**
 * UserPasswordReset
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $reset_code
 * @property string $expires_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\UserPasswordReset whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\UserPasswordReset whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\UserPasswordReset whereResetCode($value)
 * @method static \Illuminate\Database\Query\Builder|\UserPasswordReset whereExpiresAt($value)
 * @method static \Illuminate\Database\Query\Builder|\UserPasswordReset whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\UserPasswordReset whereUpdatedAt($value)
 */
class UserPasswordReset extends \Illuminate\Database\Eloquent\Model {

	protected $fillable = [];

}