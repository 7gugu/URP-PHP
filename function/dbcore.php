<?php 
date_default_timezone_set("Asia/Shanghai");//定义时区,默认为中国上海时间
$connect=mysqli_connect(DBIP,DBUSERNAME,DBPASSWORD,DBNAME,DBPORT) ;
 function query($text){
	global $connect;
	mysqli_query($connect,"set names 'utf8'");
	$res=mysqli_query($connect,"{$text}");
	return $res;
}
		/*
		Ucon 2.0 database core
		Power by 7gugu
		*/
?>