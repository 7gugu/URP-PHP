<?php 
require 'config/config.php';
$connect=mysqli_connect(DBIP,DBUSERNAME,DBPASSWORD,DBNAME) or die("���ݿ�����ʧ��");
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