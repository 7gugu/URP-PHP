-- phpMyAdmin SQL Dump

--
-- 主机: localhost
-- 生成日期: 2016 年 08 月 06 日 16:13
-- 服务器版本: 5.5.40
-- PHP 版本: 5.3.29

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `ucon`
--

-- --------------------------------------------------------

--
-- 表的结构 `inser`
--

CREATE TABLE IF NOT EXISTS `inser` (
  `id` int(11) NOT NULL,
  `inser` text NOT NULL COMMENT '激活码',
  `time` int(11) NOT NULL COMMENT '可用时间',
  `max` int(11) NOT NULL COMMENT '最大人数',
  `password` text NOT NULL COMMENT '拥有者'
) ENGINE=MyISAM DEFAULT CHARSET=gbk;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
