-- phpMyAdmin SQL Dump
--
-- 主机: localhost
-- 生成日期: 2016 年 08 月 18 日 00:07
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
-- 表的结构 `server`
--

CREATE TABLE IF NOT EXISTS `server` (
  `id` int(11) NOT NULL,
  `user` text NOT NULL,
  `time` int(11) NOT NULL,
  `rpw` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT 'rcon password',
  `rport` int(11) NOT NULL COMMENT 'rcon port',
  `port` int(11) NOT NULL COMMENT 'game port',
  `name` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '服务器名称',
  `sid` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '服务器文件夹名称',
  `players` int(11) NOT NULL COMMENT '玩家数量',
  `welcome` text NOT NULL COMMENT '欢迎信息',
  `difficult` text NOT NULL COMMENT '难度',
  `mode` text NOT NULL COMMENT '对战模式',
  `map` text NOT NULL COMMENT '地图',
  `password` text NOT NULL COMMENT '密码',
  `view` text NOT NULL COMMENT '视角',
  `cheat` text NOT NULL COMMENT '作弊',
  `loadout` text NOT NULL COMMENT '出生装备',
  `state` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=gbk;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
