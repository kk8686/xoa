CREATE TABLE IF NOT EXISTS `worker` (
	`id` integer PRIMARY KEY AUTOINCREMENT NOT NULL,
	`mobile` varchar(11) NOT NULL,
	`email` varchar(255) NOT NULL,
	`password_hash` varchar(64) NOT NULL,
	`hash_key` varchar(64) NOT NULL,
	`name` varchar(255) NOT NULL,
	`add_time` date NOT NULL
);

CREATE TABLE IF NOT EXISTS `project` (
	`id` integer PRIMARY KEY AUTOINCREMENT NOT NULL,
	`name` varchar(30) NOT NULL,
	`worker_id` integer NOT NULL,
	`add_time` date NOT NULL
);

INSERT INTO `worker` (`id`, `email`, `password_hash`, `hash_key`, `add_time`, `mobile`, `name`) VALUES (1, 'ff@yy.com', '$2y$13$CiVv2ZBR.IpOYORnF3SWOuMdFN.hLbgNtzHzg8RPXVItQ02U6LiTy', 'MSTDkhzVsWNUvW0kIIQAHyv3cQ4MO3T_', '2016-10-25', '', '');

INSERT INTO `project` (`id`, `name`, `worker_id`, `add_time`) VALUES (1, '兔子外卖', 1, '2016-10-25'), (2, '嘟嘟打车', 1, '2016-10-25'), (3, '去那儿', 1, '2016-10-25');

