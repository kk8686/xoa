CREATE TABLE IF NOT EXISTS `worker` (
	`id` integer PRIMARY KEY AUTOINCREMENT NOT NULL,
	`email` varchar(255) NOT NULL DEFAULT '',
	`mobile` varchar(11) NOT NULL DEFAULT '',
	`password_hash` varchar(64) NOT NULL DEFAULT '',
	`hash_key` varchar(64) NOT NULL DEFAULT '',
	`name` varchar(255) NOT NULL DEFAULT '',
	`avatar` varchar(255) NOT NULL DEFAULT '',
	`gender` boolean NOT NULL DEFAULT 0,
	`birthday` date NOT NULL DEFAULT '0000-00-00',
	`add_time` date NOT NULL
);

CREATE TABLE IF NOT EXISTS `project` (
	`id` integer PRIMARY KEY AUTOINCREMENT NOT NULL,
	`name` varchar(30) NOT NULL,
	`worker_id` integer NOT NULL DEFAULT 0,
	`member_ids` varchar(255) NOT NULL DEFAULT '',
	`add_time` date NOT NULL
);

CREATE TABLE IF NOT EXISTS `task_category` (
	`id` integer PRIMARY KEY AUTOINCREMENT NOT NULL,
	`name` varchar(30) NOT NULL,
	`project_id` integer NOT NULL DEFAULT 0,
	`order` smallint NOT NULL DEFAULT 0
);

CREATE TABLE IF NOT EXISTS `task` (
	`id` integer PRIMARY KEY AUTOINCREMENT NOT NULL,
	`project_id` integer NOT NULL DEFAULT 0,
	`task_category_id` integer NOT NULL DEFAULT 0,
	`creater_id` integer NOT NULL DEFAULT 0,
	`worker_ids` varchar(255) NOT NULL DEFAULT '',
	`related_member_ids` varchar(255) NOT NULL DEFAULT '',
	`title` varchar(30) NOT NULL DEFAULT '',
	`detail` text NOT NULL DEFAULT '',
	`ok` boolean NOT NULL DEFAULT FALSE,
	`order` smallint NOT NULL DEFAULT 0,
	`limit_time` datetime NOT NULL DEFAULT 0,
	`end_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
	`add_time` datetime NOT NULL DEFAULT ''
);

CREATE TABLE IF NOT EXISTS `project_invite` (
	`id` integer PRIMARY KEY AUTOINCREMENT NOT NULL,
	`project_id` integer NOT NULL DEFAULT 0,
	`worker_id` integer NOT NULL DEFAULT 0,
	`status` smallint NOT NULL DEFAULT 0,
	`add_time` date NOT NULL
);

INSERT INTO `worker` (`id`, `email`, `mobile`, `password_hash`, `hash_key`, `name`, `avatar`, `gender`, `birthday`, `add_time`) VALUES (1, '12@12.com', '13800138000', '$2y$13$KZ3A.1k5FF/lxlhVHtBcp.6wcMluJx1j9VAbuIfRs2cnitaXfatyi', 'ityMGvFoxsCRjkxcFmeQ-4E9D1-JVA-_', '陈莹莹', '/data/worker/avatar/1.jpg', 1, '1995-05-05', '2016-11-27'), (2, '99@99.com', '13800138099', '$2y$13$KZ3A.1k5FF/lxlhVHtBcp.6wcMluJx1j9VAbuIfRs2cnitaXfatyi', 'ityMGvFoxsCRjkxcFmeQ-4E9D1-JVA-_', '王自动', '/data/worker/avatar/2.jpg', 1, '2016-11-27', '2016-11-27'), (3, '13@12.com', '13800138001', '$2y$13$KZ3A.1k5FF/lxlhVHtBcp.6wcMluJx1j9VAbuIfRs2cnitaXfatyi', 'ityMGvFoxsCRjkxcFmeQ-4E9D1-JVA-_', '叶聪', '/data/worker/avatar/3.jpg', 1, '1997-12-23', '2016-11-27');

INSERT INTO `project` (`id`, `name`, `worker_id`, `member_ids`, `add_time`) VALUES (1, '兔子外卖', 1, '2', '2016-11-27'), (2, '嘟嘟打车', 1, '', '2016-11-27'), (3, '去那儿', 1, '', '2016-11-27');

INSERT INTO `task_category` (`id`, `name`, `project_id`, `order`) VALUES (1, '待处理', 1, 1), (2, '进行中', 1, 2), (3, '返修', 1, 3), (4, '已验收', 1, 4), (5, '已验收（返修）', 1, 5), (11, '2号项目的测试分类1', 2, 1);

INSERT INTO `task` (`id`, `project_id`, `task_category_id`, `creater_id`, `worker_ids`, `related_member_ids`, `title`, `detail`, `ok`, `order`, `limit_time`, `end_time`, `add_time`) VALUES (1, 1, 2, 1, '1', '', '修复登陆验证码错误3次后没有冻结账户的问题', '', 0, 1, '2016-11-28 05:44:40', '0000-00-00 00:00:00', '2016-11-27 05:44:40'), (2, 1, 2, 1, '1,2', '', '新增团购功能，仿XX网站', '', 0, 1, '2016-12-04 05:44:40', '0000-00-00 00:00:00', '2016-11-27 05:44:40'), (3, 1, 1, 1, '3', '', '准备一套mock数据，周六路演要用', '', 0, 1, '2016-12-04 05:44:40', '0000-00-00 00:00:00', '2016-11-27 05:44:40');

