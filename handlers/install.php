<?php

$this->require_admin ();

$page->layout = 'admin';

$cur = $this->installed ('saas', $appconf['Admin']['version']);

if ($cur === true) {
	$page->title = i18n_get ('Already installed');
	printf ('<p><a href="/saas/admin">%s</a></p>', i18n_get ('Continue'));
	return;
} elseif ($cur !== false) {
	$this->redirect ('/' . $appconf['Admin']['upgrade']);
}

$page->title = i18n_get ('Installing SaaS Helpers');

$error = false;
$sqldata = sql_split (file_get_contents ('apps/saas/conf/install_mysql.sql'));
db_execute ('begin');
foreach ($sqldata as $sql) {
	if (! db_execute ($sql)) {
		$error = db_error ();
		break;
	}
}

if ($error) {
	db_execute ('rollback');
	printf ('<p class="notice">%s: %s</p>', i18n_get ('Error'), $error);
	return;
}

db_execute ('commit');
printf ('<p>%s <a href="/saas/admin">%s</a></p>', i18n_get ('Install completed.'), i18n_get ('Continue'));

$this->mark_installed ('saas', $appconf['Admin']['version']);

?>