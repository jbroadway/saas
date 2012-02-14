This app contains a set of very basic helpers for building SaaS oriented websites
on top of the [Elefant framework](http://www.elefantcms.com/). It is a sort of
cookbook style app that will grow as I abstract out the various parts of the
SaaS services I'm building.

## Current functionality

### Determine account by domain/subdomain

```php
<?php

// bootstrap.php
$controller->app = new Pimple ();

$controller->app['main_domain'] = 'www.example.com';

if ($_SERVER['HTTP_HOST'] !== $controller->app['main_domain']) {
	if (saas\Account::determine ($_SERVER['HTTP_HOST'])) {
		// Account found
	} else {
		// No account, redirect to homepage
		$controller->redirect ('http://' . $controller->app['main_domain'] . '/');
	}
}

?>
```

### Is the current user the account owner

```php
<?php

if (saas\Account::is_owner ()) {
	// You're talking to the owner
}

?>
```

### Do something based on the account level

```php
<?php

switch (saas\Account::$current->level) {
	case 0:
		// Free features
		break;
	case 1:
		// Basic features
		break;
	case 2:
		// Pro features
		break;
}

?>
```

### Assign custom properties to the account

```php
<?php

saas\Account::$current->ext ('company', 'Widgets Co.');
saas\Account::$current->ext ('website', 'http://www.their-website.com/');
saas\Account::$current->put ();

?>
```

### Does the current user belong to this account?

```php
<?php

use saas\BelongsTo;

if (BelongsTo::account ()) {
	// Current user belongs to current account
}

if (! BelongsTo::account ($user_id, $account_id)) {
	// Specified user doesn't belong here
}

?>
```

### Add an account

```php
<?php

$acct = new Account (array (
	'user' => $user_id,
	'level' => $level
);
$acct->extra = array (
	// extra properties here
);
$acct->put ();

?>
```

### Add a user to an account

```php
<?php

$bt = new BelongsTo (array (
	'user' => $user_id,
	'account' => $account
);
$bt->put ();

?>
```

Note that the level is associated with the user account. It can be used
as an account level for the account holder (e.g., free, basic, pro),
as well as the type of user within the account (contributor, member, etc.).

Because it is associated with the account and not the relation, a user can
only have one level, but levels are arbitrary in meaning so you can define
as many as you want and implement your ACL rules from there.
