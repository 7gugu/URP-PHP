<?php 
require 'config/config.php';
require 'function/dbcore.php';
require 'function/mcore.php';
//ignore_user_abort(true);
set_time_limit(0);
if(isset($_GET['cron'])){
	header("Location:admin_panel.php?cron&suc=11");
}
function rcon($operate,$mode,$port,$rpw){
$address = 'localhost';
$socket = @socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
if ($socket === false) {
	echo "socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n";
} 
$result = @socket_connect($socket, $address, $port);
if($result === false) {
	echo "socket_connect() failed.\nReason: ($result) " . socket_strerror(socket_last_error($socket)) . "\n";
	}	
	if($mode==1){
$in = "login {$rpw} \r\n";
@socket_write($socket, $in, strlen($in));
	}
sleep(1);
$in=$operate."\r\n";
@socket_write($socket, $in, strlen($in));
sleep(2);
@socket_close($socket);		
		}

 function check($port){
   $ip="localhost";
	sleep(10);
    $sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
  $sock=@ socket_connect($sock,$ip, $port);
  @socket_close($sock);
return $sock;
   }
   query("update server set switch='1'where name='update'");
$cron=mysqli_fetch_array(query("select * from cron where name='cron'"));
$rocket=mysqli_fetch_array(query("select * from cron where name='rocket'"));
$time=mysqli_fetch_array(query("select * from cron where name='time'"));
if($cron['switch']==1){
//sleep($cron['time']);
	$rs=query("select * from server");
while($rows = mysqli_fetch_array($rs)){
	if(check($rows['port'])){
		rcon("save",1,$rows['rport'],$rows['rpw']);
		sleep(1);
       rcon("shutdown",1,$rows['rport'],$rows['rpw']);
}elseif($rows['state']==1){
	$port=$rows['port']+1;
exec("for /f \"tokens=1-5 delims= \" %a in ('\"netstat -ano|findstr \"^:{$port}\"\"') do taskkill /f /pid %d");
 }
query("update server set state='0'where port='{$rows['port']}'");
}
sleep(300);
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
exec("for /f \"tokens=1-5 delims= \" %a in ('\"netstat -ano|findstr \"^:{$port}\"\"') do taskkill /f /pid %d");
	}else{
query("update server set time='{$date}'where port='{$rows['port']}'");
}
}
}
   query("update server set switch='0'where name='update'");
?>