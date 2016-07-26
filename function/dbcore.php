<?php 
require 'config/config.php';
mysql_connect($db_ip,$db_username,$db_password) or die("数据库连接失败");
mysql_select_db($db_name);
mysql_query("set names 'utf8'");
function query($text){
	$res=mysql_query("{$text}");
	return $res;
}
		/*
		Ucon 2.0 user core
		Power by 7gugu
		*/
?>