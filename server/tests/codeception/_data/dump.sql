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
	`member_ids` varchar(255) NOT NULL DEFAULT '',
	`add_time` date NOT NULL
);

CREATE TABLE IF NOT EXISTS `task_category` (
	`id` integer PRIMARY KEY AUTOINCREMENT NOT NULL,
	`name` varchar(30) NOT NULL,
	`project_id` integer NOT NULL,
	`order` boolean NOT NULL DEFAULT 0
);

CREATE TABLE IF NOT EXISTS `project_invite` (
	`id` integer PRIMARY KEY AUTOINCREMENT NOT NULL,
	`project_id` integer NOT NULL DEFAULT 0,
	`worker_id` integer NOT NULL DEFAULT 0,
	`status` boolean NOT NULL DEFAULT 0,
	`add_time` date NOT NULL
);

INSERT INTO `worker` (`id`, `email`, `mobile`, `password_hash`, `hash_key`, `name`, `gender`, `birthday`, `add_time`) VALUES (1, '12@12.com', '13800138000', '$2y$13$KE0CbAaMkB.pdST5n.Ed3.VJ/XxlvhX3HG32xAqWtQQXaNnPgKgVu', 'Cs-0gOe42bWcHEkBnakVF7WcqYNIb6MQ', '陈莹莹', 1, '1995-05-05', '2016-11-08'), (2, '99@99.com', '13800138099', '$2y$13$KE0CbAaMkB.pdST5n.Ed3.VJ/XxlvhX3HG32xAqWtQQXaNnPgKgVu', 'Cs-0gOe42bWcHEkBnakVF7WcqYNIb6MQ', '王自动', 1, '2016-11-08', '2016-11-08'), (3, '13@12.com', '13800138001', '$2y$13$KE0CbAaMkB.pdST5n.Ed3.VJ/XxlvhX3HG32xAqWtQQXaNnPgKgVu', 'Cs-0gOe42bWcHEkBnakVF7WcqYNIb6MQ', '叶聪', 1, '1997-12-23', '2016-11-08');

INSERT INTO `project` (`id`, `name`, `worker_id`, `add_time`) VALUES (1, '兔子外卖', 1, '2016-11-08'), (2, '嘟嘟打车', 1, '2016-11-08'), (3, '去那儿', 1, '2016-11-08');

INSERT INTO `task_category` (`id`, `name`, `project_id`, `order`) VALUES (1, '待处理', 1, 1), (2, '进行中', 1, 1), (3, '返修', 1, 1), (4, '已验收', 1, 1), (5, '已验收（返修）', 1, 1);

