<?php 
require 'config/config.php';
require 'function/dbcore.php';
ignore_user_abort(true);
set_time_limit(0);
function restart(){
while ( $rows = mysqli_fetch_array ( query("select * from server ") ) ) {
        rcon("shutdown",1,$rows['rport'],$rows['rpw']);
	    rcon($rows['sid'],0,1935,'');
}
}
function rocket_download($key) {
$url="http://api.rocketmod.net/download/unturned/latest/".$key;   
$dir=PAHTS.'rocket.zip';
$ch = curl_init($url);
$fp = fopen($dir, "w+");
curl_setopt($ch, CURLOPT_FILE, $fp);
curl_setopt($ch, CURLOPT_HEADER, 0);
$res=curl_exec($ch);
curl_close($ch);
fclose($fp);
return $res;
}
$row=mysqli_fetch_array(query("select * from cron"));
if($row[3]['switch']==1){
	sleep($row[3]['time']);
if($row[1]['switch']==1){
rocket_download($row[1]['key']);
getzip("rocket.zip",PAHTS."unturned_data/Managed/");
}
if($row[0]['switch']==1){
	restart();
}

}

?>