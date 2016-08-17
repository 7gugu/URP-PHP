-- phpMyAdmin SQL Dump
--
-- 主机: localhost
-- 生成日期: 2016 年 08 月 18 日 00:08
-- 服务器版本: 5.5.40
-- PHP 版本: 5.3.29

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `urp`
--

-- --------------------------------------------------------

--
-- 表的结构 `cron`
--

CREATE TABLE IF NOT EXISTS `cron` (
  `name` text NOT NULL,
  `switch` int(11) NOT NULL,
  `key` text NOT NULL,
  `time` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=gbk;

--
-- 转存表中的数据 `cron`
--

INSERT INTO `cron` (`name`, `switch`, `key`, `time`) VALUES
('rocket', 1, '39D55C3F-2894-4FC4-BA7B-40B6496BC969', ''),
('cron', 1, '', '60');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
