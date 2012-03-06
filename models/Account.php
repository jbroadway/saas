<?php

namespace saas;

/**
 * User account, domain/subdomain and extended properties.
 *
 * Usage:
 *
 *   <?php
 *   
 *   use saas\Account;
 *   
 *   Account::determine ($_SERVER['HTTP_HOST']);
 *   
 *   echo Account::$current->level;
 *   
 *   ?>
 *
 * Note that if you're using this in `bootstrap.php`, you must add this
 * line first:
 *
 *   $memcache = Cache::init (conf ('Cache'));
 *
 * This initializes the cache which is used by `determine()` to eliminate
 * repeat database lookups.
 */
class Account extends \ExtendedModel {
	public $table = 'saas_account';
	public $key = 'user';
	public $_extended_field = 'extra';

	/**
	 * A static copy of the currently active account object.
	 */
	public static $current = null;

	/**
	 * Is the current user the account owner?
	 */
	public static function is_owner () {
		return (\User::val ('id') === self::$current->user);
	}

	/**
	 * Determine which account based on the hostname, full then subdomain.
	 * Allows for accounts to be associated with their own custom domain
	 * name or by subdomain.
	 */
	public static function determine ($host) {
		global $memcache;

		// Check the cache first
		$res = $memcache->get ('_saas_account_' . $host);
		if ($res) {
			self::$current = $res;
			return $res;
		}

		// Check by literal domain first
		$res = self::query ()
			->where ('domain', $host)
			->single ();

		if ($res !== false) {
			// Cache then return
			$memcache->set ('_saas_account_' . $host, $res);
			self::$current = $res;
			return $res;
		}

		$url = explode ('.', $host);
		if (count ($url) < 3) {
			// No subdomain
			return false;
		}

		// Check by subdomain next
		$res = self::query ()
			->where ('domain', array_shift ($url))
			->single ();

		if ($res !== false) {
			// Cache then return
			$memcache->set ('_saas_account_' . $host, $res);
			self::$current = $res;
			return $res;
		}

		// No account found for subdomain
		return false;
	}

	/**
	 * Clear the cache for a particular host name. Useful when an account
	 * wishes to change its subdomain, for example.
	 */
	public static function clear_host_cache ($host) {
		global $memcache;
		return $memcache->delete ('_saas_account' . $host);
	}
}

?>