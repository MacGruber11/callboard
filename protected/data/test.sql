-- --------------------------------------------------------
-- Хост:                         127.0.0.1
-- Версия сервера:               5.5.38-log - MySQL Community Server (GPL)
-- ОС Сервера:                   Win32
-- HeidiSQL Версия:              8.3.0.4694
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Дамп структуры для таблица resume-test.tbl_item
CREATE TABLE IF NOT EXISTS `tbl_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `date` int(11) DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- Дамп данных таблицы resume-test.tbl_item: ~7 rows (приблизительно)
/*!40000 ALTER TABLE `tbl_item` DISABLE KEYS */;
INSERT INTO `tbl_item` (`id`, `name`, `image`, `user_id`, `date`, `price`) VALUES
	(1, 'Test1', '1.jpg', 1, 1410709352, 255),
	(2, 'Test2', '2.jpg', 1, 1410709372, 2425),
	(3, 'test3', '3.jpg', 1, 1410709393, 5346),
	(4, 'Test4', '4.jpg', 1, 1410709408, 1355),
	(5, 'Test5', '5.jpg', 3, 1410709438, 345),
	(6, 'Test6', '6.jpg', 3, 1410709453, 1231),
	(7, 'Test7', '7.png', 3, 1410709469, 346);
/*!40000 ALTER TABLE `tbl_item` ENABLE KEYS */;


-- Дамп структуры для таблица resume-test.tbl_profiles
CREATE TABLE IF NOT EXISTS `tbl_profiles` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `lastname` varchar(50) NOT NULL DEFAULT '',
  `firstname` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`user_id`),
  CONSTRAINT `user_profile_id` FOREIGN KEY (`user_id`) REFERENCES `tbl_users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- Дамп данных таблицы resume-test.tbl_profiles: ~3 rows (приблизительно)
/*!40000 ALTER TABLE `tbl_profiles` DISABLE KEYS */;
INSERT INTO `tbl_profiles` (`user_id`, `lastname`, `firstname`) VALUES
	(1, 'Admin', 'Administrator'),
	(3, 'demo', 'demo'),
	(4, 'asdasdasd', 'asdasd');
/*!40000 ALTER TABLE `tbl_profiles` ENABLE KEYS */;


-- Дамп структуры для таблица resume-test.tbl_profiles_fields
CREATE TABLE IF NOT EXISTS `tbl_profiles_fields` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `varname` varchar(50) NOT NULL,
  `title` varchar(255) NOT NULL,
  `field_type` varchar(50) NOT NULL,
  `field_size` varchar(15) NOT NULL DEFAULT '0',
  `field_size_min` varchar(15) NOT NULL DEFAULT '0',
  `required` int(1) NOT NULL DEFAULT '0',
  `match` varchar(255) NOT NULL DEFAULT '',
  `range` varchar(255) NOT NULL DEFAULT '',
  `error_message` varchar(255) NOT NULL DEFAULT '',
  `other_validator` varchar(5000) NOT NULL DEFAULT '',
  `default` varchar(255) NOT NULL DEFAULT '',
  `widget` varchar(255) NOT NULL DEFAULT '',
  `widgetparams` varchar(5000) NOT NULL DEFAULT '',
  `position` int(3) NOT NULL DEFAULT '0',
  `visible` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `varname` (`varname`,`widget`,`visible`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Дамп данных таблицы resume-test.tbl_profiles_fields: ~2 rows (приблизительно)
/*!40000 ALTER TABLE `tbl_profiles_fields` DISABLE KEYS */;
INSERT INTO `tbl_profiles_fields` (`id`, `varname`, `title`, `field_type`, `field_size`, `field_size_min`, `required`, `match`, `range`, `error_message`, `other_validator`, `default`, `widget`, `widgetparams`, `position`, `visible`) VALUES
	(1, 'lastname', 'Last Name', 'VARCHAR', '50', '3', 1, '', '', 'Incorrect Last Name (length between 3 and 50 characters).', '', '', '', '', 1, 3),
	(2, 'firstname', 'First Name', 'VARCHAR', '50', '3', 1, '', '', 'Incorrect First Name (length between 3 and 50 characters).', '', '', '', '', 0, 3);
/*!40000 ALTER TABLE `tbl_profiles_fields` ENABLE KEYS */;


-- Дамп структуры для таблица resume-test.tbl_users
CREATE TABLE IF NOT EXISTS `tbl_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL,
  `password` varchar(128) NOT NULL,
  `email` varchar(128) NOT NULL,
  `activkey` varchar(128) NOT NULL DEFAULT '',
  `create_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lastvisit_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `superuser` int(1) NOT NULL DEFAULT '0',
  `status` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `status` (`status`),
  KEY `superuser` (`superuser`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- Дамп данных таблицы resume-test.tbl_users: ~4 rows (приблизительно)
/*!40000 ALTER TABLE `tbl_users` DISABLE KEYS */;
INSERT INTO `tbl_users` (`id`, `username`, `password`, `email`, `activkey`, `create_at`, `lastvisit_at`, `superuser`, `status`) VALUES
	(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'webmaster@example.com', '9a24eff8c15a6a141ece27eb6947da0f', '2014-07-30 17:20:59', '2014-09-14 19:40:03', 1, 1),
	(3, 'demo', 'fe01ce2a7fbac8fafaed7c982a04e229', 'demo@example.com', '7ae331332ea54ed0a5383c3b3745ccf0', '2014-08-04 19:16:53', '2014-09-14 19:43:39', 0, 1),
	(4, 'first_user', 'a8f5f167f44f4964e6c998dee827110c', 'asd@mail.com', '08f2ac768f37324a5821a62709c1235d', '2014-08-04 19:22:22', '0000-00-00 00:00:00', 0, 1),
	(5, 'asdasdasd', 'a3dcb4d229de6fde0db5686dee47145d', 'asdasd@mail.com', '899e1bf23284752319aa4f825bf8bc6c', '2014-09-05 18:39:59', '0000-00-00 00:00:00', 0, 1);
/*!40000 ALTER TABLE `tbl_users` ENABLE KEYS */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
