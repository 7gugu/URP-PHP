-- phpMyAdmin SQL Dump
--
-- 主机: localhost
-- 生成日期: 2016 年 09 月 16 日 22:03
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
  `text` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=gbk;

--
-- 转存表中的数据 `cron`
--

INSERT INTO `cron` (`name`, `switch`, `key`, `text`) VALUES
('rocket', 0, '', ''),
('cron', 0, '', ''),
('gamever', 0, '', ''),
('time', 0, '', ''),
('cmduser', 0, '', ''),
('cmdpaw', 0, '', ''),
('cmdpath', 0, '', ''),
('ustate', 0, '', ''),
('rocketver', 0, '', '');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
