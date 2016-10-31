CREATE TABLE IF NOT EXISTS `worker` (
	`id` integer PRIMARY KEY AUTOINCREMENT NOT NULL,
	`email` varchar(255) NOT NULL,
	`mobile` varchar(11) NOT NULL,
	`password_hash` varchar(64) NOT NULL,
	`hash_key` varchar(64) NOT NULL,
	`name` varchar(255) NOT NULL,
	`gender` boolean NOT NULL DEFAULT 0,
	`birthday` date NOT NULL,
	`add_time` date NOT NULL
);

CREATE TABLE IF NOT EXISTS `project` (
	`id` integer PRIMARY KEY AUTOINCREMENT NOT NULL,
	`name` varchar(30) NOT NULL,
	`worker_id` integer NOT NULL,
	`add_time` date NOT NULL
);

INSERT INTO `worker` (`id`, `email`, `mobile`, `password_hash`, `hash_key`, `name`, `gender`, `birthday`, `add_time`) VALUES (1, '12@12.com', '13800138000', '$2y$13$QSiDei5QtWta6fw5JB2erOFMDNpIpxan7JxdijyKn.FFoMixS0Pge', '__8Kn3APmFAyzDGu4oOhFh3Je6gmo6vx', '陈莹莹', 1, '1995-05-05', '2016-10-31'), (2, '99@99.com', '13800138099', '$2y$13$QSiDei5QtWta6fw5JB2erOFMDNpIpxan7JxdijyKn.FFoMixS0Pge', '__8Kn3APmFAyzDGu4oOhFh3Je6gmo6vx', '王自动', 1, '2016-10-31', '2016-10-31');

INSERT INTO `project` (`id`, `name`, `worker_id`, `add_time`) VALUES (1, '兔子外卖', 1, '2016-10-31'), (2, '嘟嘟打车', 1, '2016-10-31'), (3, '去那儿', 1, '2016-10-31');

