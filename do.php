<?php 
require 'config/config.php';
require 'function/dbcore.php';
require 'function/mcore.php';
//ignore_user_abort(true);
set_time_limit(0);
if(isset($_GET['cron'])){
	header("Location:admin_panel.php?cron&suc=11");
}
   query("update server set switch='1'where name='update'");
$rocket=mysqli_fetch_array(query("select * from cron where name='rocket'"));
$cmd=mysqli_fetch_array(query("select * from cron where name='cmd'"));
	$rs=query("select * from server");
while($rows = mysqli_fetch_array($rs)){
	$port=$rows['port']+1;
popen("for /f \"tokens=1-5 delims= \" %a in ('\"netstat -ano|findstr \"^:{$port}\"\"') do taskkill /f /pid %d",'r');
query("update server set state='0'where port='{$rows['port']}'");
}
if($cmd['switch']==1){
	$user=mysqli_fetch_array(query("select * from cron where name='cmduser'"));
	$paw=mysqli_fetch_array(query("select * from cron where name='cmdpaw'"));
popen("start ".$cmd['key']."\\steamcmd.exe +login ".$user." ".$paw." +force_install_dir ".PATHS." +app_update 304930 validate +exit","r");
sleep(300);
}
if($rocket['switch']==1){
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
?>