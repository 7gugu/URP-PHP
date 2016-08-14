<?php 
/*****************
   函数库
*****************/
function rcon($operate,$mode,$port,$rpw){
//error_reporting(E_ALL);
//port 服务器Rcon或者启动模块[1935]端口 Rpw Rcon密码 operate 指令
@set_time_limit(0);
$address = 'localhost';
$socket = @socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
if ($socket === false) {
	//echo "socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n";
	header("Location: manage.php?index&err=1");
	exit();
} 
$result = @socket_connect($socket, $address, $port);
if($result === false) {
	//echo "socket_connect() failed.\nReason: ($result) " . socket_strerror(socket_last_error($socket)) . "\n";
    header("Location: manage.php?index&err=2");
	exit();
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
		//检测服务器状态
		   function check($port){
			   $ip="localhost";
			   sleep(6);
    $sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
  $sock=@ socket_connect($sock,$ip, $port);
  @socket_close($sock);
return $sock;
   }
//检查秘钥的有效性   
   function check_key($key=""){
$url="http://api.rocketmod.net/download/unturned/latest/".$key;
$ch = curl_init($url);
ob_start();  
curl_exec($ch);  
$check = ob_get_contents() ;  
ob_end_clean(); 
//$check=@curl_multi_getcontent($ch);
curl_close($ch);
if($check=="invalid api key"||$check=="not available"){
	return false;
}else{
	return true;
}
}

function manage($sid,$switch){
	$username=$_SESSION['username'];
	$userpower=query("select serverid from user where username='{$username}'");
$ss=query("select * from server where sid='{$sid}'");
	$ss=mysqli_fetch_array($ss);
	if($username==$ss['user']||$_SESSION['sec']==1){
		if($switch=='start'){
			$command=$sid;
        rcon($command,0,1935,'');
		header("Location: manage.php?index&suc=1");
		}elseif($switch=='shutdown'){	
		sleep(2);
			$query=query("select * from server where sid='{$sid}'");
			$rom=mysqli_fetch_array($query);
			rcon("shutdown",1,$rom['rport'],$rom['rpw']);
		header("Location: manage.php?index&suc=2");
		}elseif($switch=='restart'){
			$query=query("select * from server where sid='{$sid}'");
			$rom=mysqli_fetch_array($query);
			 rcon("shutdown",1,$rom['rport'],$rom['rpw']);
		$command=$sid;
		 rcon($command,0,1935,'');
		header("Location: manage.php?index&suc=3");
		}
	}else{
		header("Location: manage.php?index&error=3");
	}
}

		function udfile($sid,$switch,$text,$file){
			$fpath = PATHS."\Servers\\{$sid}\\{$file}";
			if(is_file( $fpath )){
				$strContent = file_get_contents($fpath);
				$re=query("select * from server where sid='{$sid}'");
				$row=mysqli_fetch_array($re);
				
				if($switch=="players"){	
			$strContent = str_ireplace('Maxplayers '.$row['players'],'Maxplayers '.$text,$strContent);
           query("update server set players='{$text}'where sid='{$sid}'");   
				}
				if($switch=="rpw"){	
$strContent = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
$strContent .= "<RocketSettings xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\">\n";
$strContent .= "<RCON Enabled=\"true\" Port=\"{$row['rport']}\" Password=\"{$text}\"/>\n";
$strContent .= "<AutomaticShutdown Enabled=\"false\" Interval=\"0\" />\n";
$strContent .= "<WebConfigurations Enabled=\"false\" Url=\"\" />\n";
$strContent .= "<WebPermissions Enabled=\"false\" Url=\"\" Interval=\"180\" />\n";
$strContent .= "<LanguageCode>en</LanguageCode>\n";
$strContent .= "</RocketSettings>\n";
//其实可以改进成为循环的,但这段代码是1.0的代码,我就懒得改了
           query("update server set rpw='{$text}'where sid='{$sid}'");   
				}
				if($switch=="servername"){	
			$strContent = str_ireplace('Name '.$row['name'],'Name '.$text,$strContent);
           query("update server set name='{$text}'where sid='{$sid}'");   
				}
				if($switch=="welcome"){	
				//$text=iconv("GB2312","UTF-8//IGNORE",$text);
			$strContent = str_ireplace('Welcome '.$row['welcome'],'Welcome '.$text,$strContent);
           query("update server set welcome='{$text}'where sid='{$sid}'");   
				}
				if($switch=="difficult"){	
			$strContent = str_ireplace('Mode '.$row['difficult'],'Mode '.$text,$strContent);
           query("update server set difficult='{$text}'where sid='{$sid}'");   
				}
				if($switch=="map"){	
			$strContent = str_ireplace('Map '.$row['map'],'Map '.$text,$strContent);
           query("update server set map='{$text}'where sid='{$sid}'");   
				}
				if($switch=="password"){	
			$strContent = str_ireplace('Password '.$row['password'],'Password '.$text,$strContent);
           query("update server set password='{$text}'where sid='{$sid}'");   
				}
				
				if($switch=="view"){	
			$strContent = str_ireplace('Perspective '.$row['view'],'Perspective '.$text,$strContent);
           query("update server set view='{$text}'where sid='{$sid}'");   
				}
		        if($switch=="cheat"){
                     	if($row['cheat']==1){
							$c1="enabled";
						}else{
							$c1="disabled";
						}	
                        if($text==1){
							$c2="enabled";
						}else{
							$c2="disabled";
						}						
			$strContent = str_ireplace('cheats '.$c1,'cheats '.$c2,$strContent);
           query("update server set cheat='{$text}'where sid='{$sid}'");   
				}
		  if($switch=="mode"){	
			$strContent = str_ireplace($row['mode'],$text,$strContent);
           query("update server set mode='{$text}'where sid='{$sid}'");   
				}
				//echo $strContent;
		   $write=fopen($fpath,"w");
        fwrite($write,$strContent);
		  fclose($write);
 //echo "成功";
}else{
//	echo "失败";
}
			
		}
		
		//ZIP解压模块
		function getzip($filename, $path) {
 if(!file_exists($filename)){
  die("文件 $filename 不存在！");
 } 
 $filename = iconv("utf-8","gb2312",$filename);
 $path = iconv("utf-8","gb2312",$path);
 $resource = zip_open($filename);
 $i = 1;
 while ($dir_resource = zip_read($resource)) {
  if (zip_entry_open($resource,$dir_resource)) {
   $file_name = $path.zip_entry_name($dir_resource);
   $file_path = substr($file_name,0,strrpos($file_name, "/"));
   if(!is_dir($file_path)){
    mkdir($file_path,0777,true);
   }
   if(!is_dir($file_name)){
    $file_size = zip_entry_filesize($dir_resource);
    if($file_size<(1024*1024*6)){
     $file_content = zip_entry_read($dir_resource,$file_size);
     file_put_contents($file_name,$file_content);
    }else{
   //   return "<p> ".$i++." 此文件已被跳过，原因：文件过大， -> ".iconv("gb2312","utf-8",$file_name)." </p>";
    }
   }
   zip_entry_close($dir_resource);
  }
 }
 zip_close($resource); 
 return true;
}
	//地图上传模块
		function upmap($upgfile){
if(is_uploaded_file($upgfile['tmp_name'])){ 
$upfile=$upgfile; 
$fname=$upfile["name"];//上传文件的文件名 
$ftype=$upfile["type"];//上传文件的类型 
$fsize=$upfile["size"];//上传文件的大小 
$tmp_name=$upfile["tmp_name"];//上传文件的临时存放路径 
$tname=explode(".",$fname);
if($tname[1]  ==  "zip"  or  $tname[1]  ==  "ZIP"){ 
$error=$upfile["error"];//上传后系统返回的值 
$sid=$_COOKIE['ser'];
move_uploaded_file($tmp_name,PATHS.'//Servers/'.$sid.'/Workshop/Maps/'.$fname); 
if($error==0){
	return true;
	echo "1";
}elseif ($error==1){ 
 return "貌似大了点,小点吧!"; 
}elseif ($error==2){ 
 return "请上传低于20mb的zip地图文件"; 
}elseif ($error==3){ 
 return "貌似断网了,压缩包只上传了一半"; 
}elseif ($error==4){ 
return "上传失败啦QWQ"; 
}else{ 
return "请不要上传空压缩包好么QAQ"; 
} 
}else{ 
return "请上传ZIP格式的地图压缩包！"; 
} 
} 
		}	
		//删除目录
		function ddf( $dirName )  
{  
if ( $handle = opendir( "$dirName" ) ) {  
   while ( false !== ( $item = readdir( $handle ) ) ) {  
   if ( $item != "." && $item != ".." ) {  
   if ( is_dir( "$dirName/$item" ) ) {  
   ddf( "$dirName/$item" );  
   } else {  
   if( unlink( "$dirName/$item" ) ){
	 //  echo "成功删除文件： $dirName/$item<br />\n";  
   }else{
	   return false;
   }
   }
   }  
   }  
   closedir( $handle );  
   if( rmdir( $dirName ) ){  
   return true;
}else{
	return false;
}  
}
}  
		//删除dll
	function del($file) {
  if (file_exists($file)) {
	    $fn=explode(".",$file);
	  if(file_exists($fn[0])){
  if (unlink($file)) {
	   $dh=opendir($fn[0]);
  while ($file=readdir($dh)) {
    if($file!="." && $file!="..") {
      $fullpath=$fn[0]."/".$file;
      if(!is_dir($fullpath)) {
          unlink($fullpath);
      } else {
          del($fullpath);
      }
    }
  }
 
  closedir($dh);
  if(file_exists($fn[0])){
  if(rmdir($fn[0])){
    return "删除成功";
	exit();
  }else{
	  return "删除成功";
	exit(); 
  }
  }
   } else {
     return "dll删除失败";
	 exit();
   }
  }else{
	   if (unlink($file)) {
		   return "删除成功";
	exit(); 
	   }else{
		     return "dll删除失败";
	 exit();
	   }
  }
  }
}
//邀请码生成
function getinser($length){
   $str = null;
   $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
   $max = strlen($strPol)-1;

   for($i=0;$i<$length;$i++){
    $str.=$strPol[rand(0,$max)];
   }

   return $str;
  }
  
function fcreate($sname,$port,$rport,$rpw,$map,$mode,$pv,$cheat,$sid){ 
	$dat = "Name $sname\n";
$dat .= "Port $port\n";
$dat .= "Map $map\n";
$dat .= "Mode $mode\n";
$dat .= "Perspective both\n";
$dat .= "$pv\n";
$dat .= "Welcome 本服务器由URP强力驱动\n";
$dat .= "Password \n";
$dat .= "$cheat\n";
$f=PATHS."\Servers\\$sid\server";
if (!file_exists($f))
{ 
mkdir ($f,0777,true);
//echo "文件夹创建成功";
    }else{
  //      echo "文件夹已创建";
    }
$file=PATHS."\Servers\\$sid\server\Commands.dat";
$handle=fopen($file,"w+");
$tf=fwrite($handle,$dat);
fclose($handle); 
$xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
$xml .= "<RocketSettings xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\">\n";
$xml .= "<RCON Enabled=\"true\" Port=\"{$rport}\" Password=\"{$rpw}\"/>\n";
$xml .= "<AutomaticShutdown Enabled=\"false\" Interval=\"0\" />\n";
$xml .= "<WebConfigurations Enabled=\"false\" Url=\"\" />\n";
$xml .= "<WebPermissions Enabled=\"false\" Url=\"\" Interval=\"180\" />\n";
$xml .= "<LanguageCode>en</LanguageCode>\n";
$xml .= "</RocketSettings>\n";
$f=PATHS."\Servers\\$sid\Rocket";
if (!file_exists($f))
{ 
mkdir ($f,0777,true);
    }else{
    }
	$f=PATHS."\Servers\\$sid\Rocket\Plugins";
if (!file_exists($f))
{ 
mkdir ($f,0777,true);
//echo "文件夹创建成功";
    }else{
  //      echo "文件夹已创建";
    }
$file=PATHS."\Servers\\$sid\Rocket\Rocket.config.xml";
$handle=fopen($file,"w+");
fwrite($handle,$xml);
fclose($handle); 
if($tf==false){
	return false;
}else{
	return true;
}
}
//地图文件列表
function gfl($mode){ 
	if($mode==0){
foreach(glob(PATHS.'\Servers\\'.$_COOKIE['ser'].'\Workshop\Maps\\*', GLOB_BRACE) as $afile){ 
if(is_dir($afile)) 
{ 
$path=PATHS.'\Servers\\'.$_COOKIE['ser'].'\Workshop\Maps\\';
echo "<option value='".str_replace($path,'',$afile)."'>".str_replace($path,'',$afile)."</option>";
} 
}
}elseif($mode==1){
	var_dump(glob(PATHS.'\\Maps\\*', GLOB_BRACE));
	foreach(glob(PATHS.'\\Maps\\*', GLOB_BRACE) as $afile){ 
	echo "1";
if(is_dir($afile)) 
{ 
echo "<option value='".str_replace(PATHS.'\Maps\\','',$afile)."'>".str_replace(PATHS.'\Maps\\','',$afile)."</option>";
} 
}
} 
}
//文件读写
function rwfile($path,$switch,$text){
			$fpath = $path;
			if($switch=="r"){
				if(is_file( $fpath )){
 $text = file_get_contents( $fpath );
 return $text;
}
			}elseif($switch=="w"){
								if(is_file( $fpath )){
        $write=fopen($fpath,"w");
        fwrite($write,$text);
        fclose($write);
 return true;
}else{
	return false;
}
			}
		}
//插件文件列表
function plist($path,$mode){ 


if($mode=="dll"){
		$arr=glob($path."/*",GLOB_BRACE);
		$ac=count($arr);
		if($ac==1){
			$afile=$arr[0];
			$a=$arr;
			$a=array_values($a);
	if(is_dir($afile)==false){	
		$sn=explode(".",$afile);	
		if(array_search($sn[0],$a) === false){
			
			$ps=str_replace($path."/","",$afile);
			  echo "<tr><td></td>";
  echo "<td>".$ps."</td>";
  echo "<td>无配置文件</td><td><a href='manage.php?del&po=".$ps."' >删除插件</a></td></tr>";	
		}
			}
			
		}elseif($ac==0){
			  echo "<tr><td></td>";
  echo "<td>无已安装插件</td>";
  echo "<td></td><td>戳下面安装插件</td></tr>";
		}else{
		foreach($arr as $afile){
			if(is_dir($afile)){
			continue;	
			}else{
	$a=$arr;
	unset($a[array_search($afile,$a)]);
	$a=array_values($a);
		foreach( $a as $s ){	
			$fn=explode(".",$afile);		
   if ( strpos( $s , $fn[0] ) !== false ){
	 //  echo "插件:".$afile;
      //   echo "插件文件夹:".$s."<BR>";
	  $pa=str_replace($path."/","",$afile);
	  $ps=str_replace($path."/","",$s);
	    echo "<tr><td></td>";
  echo "<td>".$pa."</td>";
  echo "<td>
  
  <a href='manage.php?po=".$ps."' >设置</a></td><td><a href='manage.php?del&po=".$pa."' >删除插件</a>
  </td></tr>";
		}else
			if(is_dir($s)==false){	
		$sn=explode(".",$s);	
		if(array_search($sn[0],$a) === false){
			
			$ps=str_replace($path."/","",$s);
			  echo "<tr><td></td>";
  echo "<td>".$ps."</td>";
  echo "<td>无配置文件</td><td><a href='manage.php?del&po=".$ps."' >删除插件</a></td></tr>";
		//	 echo "插件:".$s;
      //   echo "插件文件夹:不存在<BR>";			
		}
			}		
}
		}
		}
}		
	
	
	
}elseif($mode=="xml"){
	foreach(glob($path."/*.xml",GLOB_BRACE) as $afile){ 
if(is_dir($afile)) 
{ 
}else{
	$a=str_replace($path,'',$afile);
  $name=explode('.',$a);
	   echo "<tr><td></td>";
  echo "<td>".$a."</td>";
  echo "<td><a href='manage.php?pfile=".$afile."' >编辑</a></td><td></td></tr>";
}
}
}
}
function pshop($path){
foreach(glob($path."*.dll",GLOB_BRACE) as $afile){ 
if(!is_dir($afile)) 
{ 
$a=str_replace($path,'',$afile);
  $name=explode('.',$a);
   echo "<tr>";
  echo "<td>".$a."</td>";
  $rs=query("select * from plugin where name='{$a}'");
  $row=mysqli_fetch_array($rs);
 $fp=str_replace("\\","/",$afile);
  echo "<td>{$row['state']}</td><td><button type='button'  onclick=\"javascript:window.location.href='manage.php?shop&move=$fp'\" class='am-btn am-btn-secondary'>
		添加到服务器</button></td>
  </tr>";
}
} 
}

		/*
		Ucon 2.0 manage core
		Power by 7gugu
		*/
?>