<?php
//定时任务模块
require 'config/config.php';
require 'function/dbcore.php';
require 'function/mcore.php';
set_time_limit(0);
$api="";
$rocket=mysqli_fetch_array(query("select * from cron where name='rocket'"));
$time=mysqli_fetch_array(query("select * from cron where name='time'"));
$cmd=mysqli_fetch_array(query("select * from cron where name='cmdpath'"));
if($rocket['key']!=""){
$api=json_decode(file_get_contents("https://ci.rocketmod.net/job/Rocket.Unturned/api/json?pretty=true"),true);
sleep(10);
echo "检测完成|开始更新";
sleep(10);
}else{
    echo "不启用版本检测\n";
}
$rocketver=false;
$rocket=mysqli_fetch_array(query("select * from cron where name='rocketver'"));
if($api!=""){
if($rocket['key']!=""){
    if($api['builds'][0]['number']!=$rocket['key']){
        $rocketver=true;echo "2";
    }
}else{
    $rocketver=true;
}
$r=$api['builds'][0]['number'];
echo "RocketVersion:".$r;
query("update cron set `key`='{$r}'where `name`='rocketver'");
}else{
    echo "未检测版本";
    sleep(5);
}
$update=mysqli_fetch_array(query("select * from cron where name='update'"));
recurse_copy(PATHS."\Servers",PATHS."\huifu");//备份文件
//var_dump($update);//sleep(60);
//--------工作模块------------
if($update['switch']==0){
	query("update cron set `switch`='1' where `name`='update'");
    $rs=query("select * from server");
while($rows = mysqli_fetch_array($rs)){
    $port=$rows['port']+1;
 system("for /f \"tokens=1-5 delims= \" %a in ('\"netstat -ano|findstr \"^:{$port}\"\"') do taskkill /f /pid %d ");
query("update server set state='0'where port='{$rows['port']}'");
}
//游戏更新
if(OSTYPE){
if($cmd['switch']==1){
    $user=mysqli_fetch_array(query("select * from cron where name='cmduser'"));
    $paw=mysqli_fetch_array(query("select * from cron where name='cmdpaw'"));
    if(file_exists($cmd['key']."\\steamcmd.exe")){
system("start ".$cmd['key']."\\steamcmd.exe +login ".$user['key']." ".$paw['key']." +force_install_dir ".PATHS." +app_update 304930 validate +exit");
sleep(300);
}else{
    echo "Fail to update the game by lack of steamcmd.exe\r\n ";
}
}
}
//Rocket更新
if($rocket['switch']==1){
if(OSTYPE){$url="https://ci.rocketmod.net/job/Rocket.Unturned/lastSuccessfulBuild/artifact/Rocket.Unturned/bin/Release/Rocket.zip";}else{$url="https://ci.rocketmod.net/job/Rocket.Unturned%20Linux/lastSuccessfulBuild/artifact/Rocket.Unturned/bin/Release/Rocket.zip";}
rocket_download($url);
getzip(PATHS."/Rocket.zip",PATHS."/");
sleep(60);
}
$rs=query("select * from server");
while($rows = mysqli_fetch_array($rs)){
        $rows = mysqli_fetch_array (query("select * from server where port='{$rows['port']}'"));
        if($rows!=false){
			echo $rows['sid'];
			if($rows['sid']==""){
				continue;
			}
			sleep(5);
       if(OSTYPE==TRUE){
            if(SWAY==TRUE){
            //Windows环境				
                rcon($command,0,1935,'');}else{
         system("start".PATHS."\\Unturned.exe -nographics -batchmode -silent-crashes +secureserver/".$command);
		}}else{
			//Linux环境	
			system("cd /".PATHS."/Scripts");
			system("./start.sh ".$sid);
		}
      query("update server set `state`='1'where `port`='{$rows['port']}'");
        }
		$dt=date('ymd',time());
		if($time['key']!=$dt){
			query("update cron set `key`='{$dt}'where `name`='time'");
		if($time['switch']==1){
		    $date=$rows['time'];
		    $date=$date-1;
	echo $date;
    if($date<=-5){
        $sid=$rows['sid'];
       ddf(PATHS."//Servers//{$sid}//"); //到期删除服务器文件夹[慎用!]
       query("delete from server where sid='{$sid}'");
    }elseif($date<=0){
        rcon("save",1,$rows['rport'],$rows['rpw']);
        $port=$rows['port']+1;
		echo $port."\n";
 system("for /f \"tokens=1-5 delims= \" %a in ('\"netstat -ano|findstr \"^:{$port}\"\"') do taskkill /f /pid %d ");
    }
query("update server set `time`='{$date}'where `port`='{$rows['port']}'");
}
		}else{
						query("update cron set `key`='{$dt}'where `name`='time'");
		}
}
query("update cron set `switch`='0' where `name`='update'");
sleep(10);
}else{
	echo "Server update\n";sleep(10);
}
?>