<?php

namespace saas;

/**
 * Which account a user belongs to.
 *
 * Usage:
 *
 *   <?php
 *   
 *   use saas\BelongsTo;
 *   use saas\Account;
 *   
 *   if (\User::is_valid ()) {
 *     $belongs_to = new BelongsTo (\User::val ('id'));
 *     
 *     if ($belongs_to->account != Account::$current->user) {
 *       // User doesn't belong to current account
 *     }
 *   }
 *   
 *   ?>
 */
class BelongsTo extends Model {
	public $table = 'saas_belongs_to';
	public $key = 'user';

	/**
	 * Determine if a user (ID) belongs to the specified account (ID).
	 * Note: Accepts ID values, not objects.
	 *
	 * If no user is specified, it will use the current user ID
	 * from `\User::val ('id')`.
	 *
	 * If no account is specified, it will use the current account
	 * from `\saas\Account::$current->user`.
	 */
	public static function account ($user = false, $account = false) {
		$user = $user ? $user : User::val ('id');
		$account = $account ? $account : Account::$current->user;

		return db_shift (
			'select count(*) from saas_belongs_to where user = ? and account = ?',
			$user,
			$account
		);
	}
}

?>