/**
 * saas\Account - Account level, domain/subdomain, and other properties.
 * Primary key matches user ID.
 */
create table saas_account (
	user int not null primary key,
	domain char(60) not null,
	level int not null,
	extra text not null,
	index (domain)
);

/**
 * saas\BelongsTo - Which account a user belongs to.
 */
create table sass_belongs_to (
	user int not null primary key,
	account int not null,
	index (account)
);
