CREATE TABLE IF NOT EXISTS `worker` (
	`id` integer PRIMARY KEY AUTOINCREMENT NOT NULL,
	`mobile` varchar(11) NOT NULL,
	`email` varchar(255) NOT NULL,
	`password_hash` varchar(64) NOT NULL,
	`hash_key` varchar(64) NOT NULL,
	`name` varchar(255) NOT NULL,
	`add_time` date NOT NULL
);

