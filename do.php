<?php 
require 'config/config.php';
require 'function/dbcore.php';
require 'function/mcore.php';
//ignore_user_abort(true);
set_time_limit(0);
$rocket=mysqli_fetch_array(query("select * from cron where name='rocket'"));
$cmd=mysqli_fetch_array(query("select * from cron where name='cmdpath'"));
$api=json_decode(file_get_contents("http://api.rocketmod.net/status/unturned/",$key),true);
$server=false;
$rocketver=false;
$game=mysqli_fetch_array(query("select * from cron where name='gamever'"));
$rocketver=mysqli_fetch_array(query("select * from cron where name='rocketver'"));
if($game['key']!=""&&$rocket['key']!=""){
	if($game['key']!="Unturned"){
	if($api[0]['gameversion']>$game['key']){
		$server=true;
	}
	}
	if($rocketver['key']!="Unturned"){
	if($api[0]['rocketversion']>$rocketver['key']){
		$rocketver=true;
	}
	}
}else{
	$server=true;
	$rocketver=true;
}
$g=$api[0]['gameversion'];
$r=$api[0]['rocketversion'];
query("update cron set key='{$g}'where name='gamever'");
query("update cron set key='{$r}'where name='rocketver'");
//--------工作模块------------
if($server==true||$rocketver==true||$server==true&&$rocketver==true){
	$rs=query("select * from server");
while($rows = mysqli_fetch_array($rs)){
	$port=$rows['port']+1;
popen("for /f \"tokens=1-5 delims= \" %a in ('\"netstat -ano|findstr \"^:{$port}\"\"') do taskkill /f /pid %d",'r');
query("update server set state='0'where port='{$rows['port']}'");
}
//游戏更新
if($cmd['switch']==1&&$server==true){
	$user=mysqli_fetch_array(query("select * from cron where name='cmduser'"));
	$paw=mysqli_fetch_array(query("select * from cron where name='cmdpaw'"));
popen("start ".$cmd['key']."\\steamcmd.exe +login ".$user." ".$paw." +force_install_dir ".PATHS." +app_update 304930 validate +exit","r");
sleep(300);
}
//Rocket更新
if($rocket['switch']==1&&$rocketver==true){
rocket_download($rocket['key']);
getzip(PATHS."/Rocket.zip",PATHS."/unturned_data/Managed/");
}
$rs=query("select * from server");
while($rows = mysqli_fetch_array($rs)){
	    $rows = mysqli_fetch_array (query("select * from server where port='{$rows['port']}'"));
		if($rows!=false){
	   rcon($rows['sid'],0,1935,'');
	  query("update server set state='1'where port='{$rows['port']}'");
		}
}
if($time['switch']==1){
	$rs=query("select * from server");
while($rows = mysqli_fetch_array($rs)){
	$date=$rows['time'];
	$date=$date-1;
	if($date<=-5){
		$sid=$rows['sid'];
		ddf(PATHS."//Servers//{$sid}//");
		query("delete from server where sid='{$sid}'");
	}elseif($date<=0){
		rcon("save",1,$rows['rport'],$rows['rpw']);
		$port=$rows['port']+1;
popen("for /f \"tokens=1-5 delims= \" %a in ('\"netstat -ano|findstr \"^:{$port}\"\"') do taskkill /f /pid %d",'r');
	}else{
query("update server set time='{$date}'where port='{$rows['port']}'");
}
}
}
}
?>