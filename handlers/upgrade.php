<?php

$this->require_admin ();

$page->layout = 'admin';

if ($this->installed ('saas', $appconf['Admin']['version']) === true) {
	$page->title = i18n_get ('Already up-to-date');
	printf ('<p><a href="/saas/admin">%s</a></p>', i18n_get ('Continue'));
	return;
}

$page->title = i18n_get ('Upgrading SaaS Helpers');

printf ('<p>%s <a href="/saas/admin">%s</a></p>', i18n_get ('Upgrade completed.'), i18n_get ('Continue'));

$this->mark_installed ('saas', $appconf['Admin']['version']);


?>