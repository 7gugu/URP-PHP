<?php 
/*****************
   函数库
*****************/

//下载Rocket.dll
function rocket_download($key) {
$url="http://api.rocketmod.net/download/unturned/latest/".$key;   
$dir=PATHS.'/Rocket.zip';
$ch = curl_init($url);
$fp = fopen($dir, "w+");
curl_setopt($ch, CURLOPT_FILE, $fp);
curl_setopt($ch, CURLOPT_HEADER, 0);
$res=curl_exec($ch);
curl_close($ch);
fclose($fp);
return $res;
}

//Rcon函数
function rcon($operate,$mode,$port,$rpw){
//port 服务器Rcon或者启动模块[1935]端口 Rpw Rcon密码 operate 指令
@set_time_limit(0);
$address = 'localhost';
$socket = @socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
if ($socket === false) {
    header("Location: manage.php?index&err=1");
    exit();
} 
$result = @socket_connect($socket, $address, $port);
if($result === false) {
    if($port==1935){
        echo "后端应用连接失败,请联系管理员";
    }else{
    return false;
    }
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
    sleep(10);
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
curl_close($ch);
if($check=="invalid api key"||$check=="not available"){
    return false;
}else{
    return true;
}
}

//管理服务器状态函数
function manage($sid,$switch){
    $username=$_SESSION['username'];
    $userpower=query("select serverid from user where username='{$username}'");
$ss=query("select * from server where sid='{$sid}'");
    $ss=mysqli_fetch_array($ss);
    if($username==$ss['user']||$_SESSION['sec']==1){
        if($switch=='start'){
            $command=$sid;
            if(SWAY){ 
                rcon($command,0,1935,'');}else{
         system("start".PATHS."\\Unturned.exe -nographics -batchmode -silent-crashes +secureserver/".$command);
                }
        header("Location: manage.php?index&suc=1");
        }elseif($switch=='shutdown'){	
        sleep(2);
            $query=query("select * from server where sid='{$sid}'");
            $rom=mysqli_fetch_array($query);
                rcon("save",1,$rom['rport'],$rom['rpw']);
                sleep(1);
            rcon("shutdown",1,$rom['rport'],$rom['rpw']);
$port=$rom['port']+1;
 system("for /f \"tokens=1-5 delims= \" %a in ('\"netstat -ano|findstr \"^:{$port}\"\"') do taskkill /f /pid %d ");
        header("Location: manage.php?index&suc=2");
        }elseif($switch=='restart'){
            $query=query("select * from server where sid='{$sid}'");
            $rom=mysqli_fetch_array($query);
             rcon("shutdown",1,$rom['rport'],$rom['rpw']);
             	$port=$rom['port']+1;
 system("for /f \"tokens=1-5 delims= \" %a in ('\"netstat -ano|findstr \"^:{$port}\"\"') do taskkill /f /pid %d ");
        $command=$sid;
        if(SWAY){ 
                rcon($command,0,1935,'');}else{
         system("start".PATHS."\\Unturned.exe -nographics -batchmode -silent-crashes +secureserver/".$command);
                }
        header("Location: manage.php?index&suc=3");
        }
    }else{
        header("Location: manage.php?index&error=3");
    }
}

//编辑游戏服务器配置文件
function udfile($sid,$switch,$text,$file){
            $fpath = PATHS."\Servers\\{$sid}\\{$file}";
			//echo $fpath;
            if(is_file( $fpath )&&filesize($fpath)!=0){
                $strContent = file_get_contents($fpath);
                $re=query("select * from server where sid='{$sid}'");
                $row=mysqli_fetch_array($re);
                
                if($switch=="players"){	
				  if($text==""){
		  $text=1;
	                                      }
            $strContent = str_ireplace('Maxplayers '.$row['players'],'Maxplayers '.$text,$strContent,$i);
			if($i==0){
				$write=fopen($fpath,"a");
				fwrite($write,'Maxplayers '.$text."\r\n");
				fclose($write);
			}else{
				file_put_contents($fpath,$strContent."\n");
			}
           query("update server set players='{$text}'where sid='{$sid}'");   
                }
                if($switch=="servername"){				
            $strContent = str_ireplace('Name '.$row['name'],'Name '.$text,$strContent,$i);
			if($i==0){
				$write=fopen($fpath,"a");
				fwrite($write,'Name '.$text."\r\n");
				fclose($write);
			}else{
				file_put_contents($fpath,$strContent."\n");
			}
           query("update server set name='{$text}'where sid='{$sid}'");   
				}
                if($switch=="welcome"){	
                //$text=iconv("GB2312","UTF-8//IGNORE",$text);
            $strContent = str_ireplace('Welcome '.$row['welcome'],'Welcome '.$text,$strContent,$i);
			if($i==0){
				$write=fopen($fpath,"a");
				fwrite($write,'Welcome '.$text."\r\n");
				fclose($write);
			}else{
			file_put_contents($fpath,$strContent."\n");
			}
           query("update server set welcome='{$text}'where sid='{$sid}'");   
                }
                if($switch=="difficult"){	
            $strContent = str_ireplace('Mode '.$row['difficult'],'Mode '.$text,$strContent,$i);
			if($i==0){
				$write=fopen($fpath,"a");
				fwrite($write,'Mode '.$text."\r\n");
				fclose($write);
			}else{
				file_put_contents($fpath,$strContent."\n");
			}
           query("update server set difficult='{$text}'where sid='{$sid}'");   
                }
                if($switch=="map"){	
            $strContent = str_ireplace('Map '.$row['map'],'Map '.$text,$strContent,$i);
			if($i==0){
				$write=fopen($fpath,"a");
				fwrite($write,'Map '.$text."\r\n");
				fclose($write);
			}else{
				file_put_contents($fpath,$strContent."\n");
			}
           query("update server set map='{$text}'where sid='{$sid}'");   
                }
                if($switch=="password"){	
            $strContent = str_ireplace('Password '.$row['password'],'Password '.$text,$strContent,$i);
			if($i==0){
				$write=fopen($fpath,"a");
				fwrite($write,'Password '.$text."\r\n");
				fclose($write);
			}else{
				file_put_contents($fpath,$strContent."\n");
			}
           query("update server set password='{$text}'where sid='{$sid}'");   
                }
                
                if($switch=="view"){	
            $strContent = str_ireplace('Perspective '.$row['view'],'Perspective '.$text,$strContent,$i);
			if($i==0){
				$write=fopen($fpath,"a");
				fwrite($write,'Perspective '.$text."\r\n");
				fclose($write);
			}else{
				file_put_contents($fpath,$strContent."\n");
			}
           query("update server set view='{$text}'where sid='{$sid}'");   
                }
			
				if($switch=="loadout"){	
            $strContent = str_ireplace('loadout '.$row['loadout'],'loadout '.$text,$strContent,$i);
			if($i==0){
				$write=fopen($fpath,"a");
				fwrite($write,'loadout '.$text."\r\n");
				fclose($write);
				
			}else{
				file_put_contents($fpath,$strContent."\n");
			}
			$text=str_ireplace("/","\/",$text);
           query("update server set loadout='{$text}'where sid='{$sid}'");   
                }
                if($switch=="cheat"){
                     	if($row['cheat']==1){
                            $c1="enable";
                        }else{
                            $c1="disable";
                        }	
                        if($text==1){
                            $c2="enable";
                        }else{
                            $c2="disable";
                        }						
            $strContent = str_ireplace('cheats '.$c1,'cheats '.$c2,$strContent,$i);
			if($i==0){
				$write=fopen($fpath,"a+");
				fwrite($write,'cheats '.$c2."\r\n");
				fclose($write);
			}else{
				file_put_contents($fpath,$strContent."\n");
			}
			
           query("update server set cheat='{$text}'where sid='{$sid}'");   
                }
          if($switch=="mode"){	
            $strContent = str_ireplace($row['mode'],$text,$strContent,$i);
			if($i==0){
				$write=fopen($fpath,"a");
				fwrite($write,$text."\r\n");
				fclose($write);
			}else{
			file_put_contents($fpath,$strContent."\n");
			}
		   query("update server set mode='{$text}'where sid='{$sid}'");   
                }
                //echo $strContent;
 //echo "成功";
}elseif(filesize($fpath)==0)
{
	$write=fopen($fpath,"a");
	  if($switch=="players"){	
	  if($text==""){
		  $text=1;
	  }
	   fwrite($write,'Maxplayers '.$text."\r\n");
           query("update server set players='{$text}'where sid='{$sid}'");   
                }
                if($switch=="servername"){				
			fwrite($write,'Name '.$text."\r\n");
           query("update server set name='{$text}'where sid='{$sid}'");   
				}
                if($switch=="welcome"){	
                //$text=iconv("GB2312","UTF-8//IGNORE",$text);
			fwrite($write,'Welcome '.$text."\r\n");
           query("update server set welcome='{$text}'where sid='{$sid}'");   
                }
                if($switch=="difficult"){	
           fwrite($write,'Mode '.$text."\r\n");
		   query("update server set difficult='{$text}'where sid='{$sid}'");   
                }
				
                if($switch=="map"){	
           fwrite($write,'Map '.$text."\r\n");
		   query("update server set map='{$text}'where sid='{$sid}'");   
                }
                if($switch=="password"){	
           fwrite($write,'Password '.$text."\r\n");
		   query("update server set password='{$text}'where sid='{$sid}'");   
                }
                
                if($switch=="port"){	
           fwrite($write,'Port '.$text."\r\n");
		  // query("update server set `port`='{$text}'where sid='{$sid}'");   
                }
				  if($switch=="view"){	
           fwrite($write,'Perspective '.$text."\r\n");
		   query("update server set view='{$text}'where sid='{$sid}'");   
                }
				 if($switch=="cycle"){
if($text==""){
	$text=1;
}					
           fwrite($write,'cycle '.$text."\r\n");
		   query("update server set cycle='{$text}'where sid='{$sid}'");   
                }
				 if($switch=="chatrate"){	
				 if($text==""){
	$text=1;
}	
           fwrite($write,'chatrate '.$text."\r\n");
		   query("update server set chatrate='{$text}'where sid='{$sid}'");   
                }
				if($switch=="loadout"){	
           fwrite($write,'loadout '.$text."\r\n");
		   query("update server set loadout='{$text}'where sid='{$sid}'");   
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
           fwrite($write,'cheats '.$c2."\r\n");
		   query("update server set cheat='{$text}'where sid='{$sid}'");   
                }
          if($switch=="mode"){	
           fwrite($write,$text."\r\n");
		   query("update server set mode='{$text}'where sid='{$sid}'");   
                }
				fclose($write);
}else{
//	echo "失败";
}
            
        }
        
//ZIP解压模块
function getzip($filename, $path) {
 if(!file_exists($filename)){
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

//mod上传模块
function upmod($upgfile){
if(is_uploaded_file($upgfile['tmp_name'])){ 
$upfile=$upgfile; 
$fname=$upfile["name"];//上传文件的文件名 
$ftype=$upfile["type"];//上传文件的类型 
$fsize=$upfile["size"];//上传文件的大小 
$tmp_name=$upfile["tmp_name"];//上传文件的临时存放路径 
$tname=explode(".",$fname);
if($tname[count($tname)-1]  ==  "zip"  or  $tname[count($tname)-1]  ==  "ZIP"){ 
$error=$upfile["error"];//上传后系统返回的值 
$sid=$_COOKIE['ser'];
move_uploaded_file($tmp_name,PATHS.'//Servers/'.$sid.'/Workshop/Content/'.$fname); 
/*if($error==0){
    return true;
    echo "1";
}elseif ($error==1){ 
 echo "貌似大了点,小点吧!"; 
}elseif ($error==2){ 
 echo "请上传低于20mb的zip地图文件"; 
}elseif ($error==3){ 
 echo "貌似断网了,压缩包只上传了一半"; 
}elseif ($error==4){ 
echo "上传失败啦QWQ"; 
}else{ 
echo "请不要上传空压缩包好么QAQ"; 
} 
exit();*/
if($error==0){
    return true;
}else{ 
return false; 
} 
} 
        }
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
if($tname[count($tname)-1]  ==  "zip"  or  $tname[count($tname)-1]  ==  "ZIP"){ 
$error=$upfile["error"];//上传后系统返回的值 
$sid=$_COOKIE['ser'];
move_uploaded_file($tmp_name,PATHS.'//Servers/'.$sid.'/Workshop/Maps/'.$fname); 
if($error==0){
    return true;
}else{ 
return false; 
} 
} 
        }
        }
        
//插件上传
function upplugin($upgfile){
if (!file_exists("plugins/")){mkdir ("plugins/",0777,true);}
if(is_uploaded_file($upgfile['tmp_name'])){ 
$upfile=$upgfile; 
$fname=$upfile["name"];//上传文件的文件名 
$tmp_name=$upfile["tmp_name"];//上传文件的临时存放路径 
$tname=explode(".",$fname);
if($tname[count($tname)-1]  ==  "dll"  or  $tname[count($tname)-1]  ==  "DLL"){ 
$error=$upfile["error"];//上传后系统返回的值 
move_uploaded_file($tmp_name,'./plugins/'.$fname); 
if($error==0){
    return true;
} 
}else{ 
return false; 
} 
} 
        }
        
//删除游戏数据请务必三思后再启用
function ddf($dirName){
if(DDFS){  
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
}else{
   return true; 
}
  }
  
//删除插件
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

//创建用户数据库
function csql($dbname,$sqluser,$sqlpaw){
    if(CSQL){
$arr=mysqli_fetch_array(query("select * from mysql.user where User='$sqluser'"));
query("create database $dbname");
if($arr['User']==""){
query("insert into mysql.user(Host,User,Password) values('localhost','$sqluser',password('$sqlpaw'))");
query("flush privileges");
query("grant all privileges on $dbname.* to '$sqluser'@'localhost' identified by '$sqlpaw'");
query("REVOKE drop ON $dbname.* FROM '$sqluser'@'localhost'");
                    }
}
}

//生成激活码
function getinser($length){
   $str = null;
   $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
   $max = strlen($strPol)-1;
   for($i=0;$i<$length;$i++){
    $str.=$strPol[rand(0,$max)];
   }
   return $str;
  }

//生成相关配置文件
function fcreate($sname,$port,$rport,$rpw,$map,$mode,$pv,$cheat,$sid,$players,$loadout=''){ 
$loadout=htmlspecialchars($loadout);
$loadout=str_ireplace("/","//",$loadout);
$dat = "Name $sname\n";
$dat .= "Port $port\n";
$dat .= "Maxplayers $players\n";
$dat .= "Map $map\n";
$dat .= "Mode $mode\n";
$dat .= "Perspective both\n";
$dat .= "$pv\n";
$dat .= "Welcome 本服务器由URP强力驱动\n";
$dat .= "Password \n";
$dat .= "$cheat\n";
$dat .= "loadout $loadout\n";
$f=PATHS."\Servers\\$sid\server";
if (!file_exists($f))
{ 
mkdir ($f,0777,true);
//echo "文件夹创建成功";
    }else{
  //echo "文件夹已创建";
    }
    $f1=PATHS."\Servers\\$sid\\Rocket\Plugins";
    if (!file_exists($f1))
{ 
mkdir ($f1,0777,true);
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

//编辑器文件读写
function rwfile($path,$switch,$text){
            $fpath = $path;
            if($switch=="r"){
                if(is_file( $fpath )){
 $text = file_get_contents( $fpath );
 	$text=trim($text,"\0\t");
        $text=trim($text,"\xEF\xBB\xBF");
    $charset[1] = substr($text, 0, 1);  
$charset[2] = substr($text, 1, 1);  
$charset[3] = substr($text, 2, 1);  
if (ord($charset[1]) == 239 && ord($charset[2]) == 187 && ord($charset[3]) == 191) {   
   $text = substr($text, 3);
  
}
$text=htmlspecialchars($text);
 return $text;
}
            }elseif($switch=="w"){
                                if(is_file( $fpath )){
        $text=trim($text," ");
        $text=trim($text,"\xEF\xBB\xBF");
    $charset[1] = substr($text, 0, 1);  
$charset[2] = substr($text, 1, 1);  
$charset[3] = substr($text, 2, 1);  
if (ord($charset[1]) == 239 && ord($charset[2]) == 187 && ord($charset[3]) == 191) {   
   $text = substr($text, 3);
}
        file_put_contents($fpath,$text);
 return true;
}else{
    return false;
}
            }
        }
        
//玩家数量
function players(){
    $ser=$_COOKIE['ser'];
	$path=PATHS."\Servers\\$ser\\Rocket\\Logs\\Rocket.log";
	 $path = iconv("utf-8","gb2312",$path);
    $file=file_get_contents($path);
    $p=0;
        $p=$p+substr_count($file,"Connecting");
        if(substr_count($file,"Disconnecting")){
            $p=$p-substr_count($file,"Disconnecting");
        }
    echo $p."人";
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
			$pfile=str_replace(".dll","",$afile);
			if(in_array($pfile,$arr)){
echo "<tr><td></td>";
  echo "<td>".str_replace($path."/","",$afile)."</td>";
  echo "<td>
  <a href='manage.php?po=".$pfile."' >设置</a></td><td><a href='manage.php?del&po=".$afile."' >删除插件</a>
  </td></tr>";
			}else{
  echo "<tr><td></td>";
  echo "<td>".str_replace($path."/","",$afile)."</td>";
  echo "<td>无配置文件</td><td><a href='manage.php?del&po=".$afile."' >删除插件</a></td></tr>";
			}
			}
}		
		}
}elseif($mode=="xml"){
    foreach(glob($path."/*",GLOB_BRACE) as $afile){ 
if(is_dir($afile)) 
{$a=str_replace($path."/",'',$afile);
  $name=explode('.',$a);
       echo "<tr><td></td>";
  echo "<td>".$a."</td>";
  echo "<td><a href='manage.php?po=".$afile."' >打开</a></td><td></td></tr>";}else{
    $a=str_replace($path."/",'',$afile);
  $name=explode('.',$a);
       echo "<tr><td></td>";
  echo "<td>".$a."</td>";
  echo "<td><a href='manage.php?pfile=".$afile."' >编辑</a></td><td></td></tr>";
}
}
}
}

//插件列表
function pshop($path){
foreach(glob($path."*.dll",GLOB_BRACE) as $afile){ 
if(!is_dir($afile)) 
{ 
$a=str_replace($path,'',$afile);
  $name=explode('.',$a);
   echo "<tr>";
  echo "<td>".$name[0]."</td>";
  $rs=query("select * from plugin where name='{$a}'");
  $row=mysqli_fetch_array($rs);
 $fp=str_replace("\\","/",$afile);
  echo "<td>{$row['state']}</td><td><button type='button'  onclick=\"javascript:window.location.href='manage.php?shop&move=$fp'\" class='am-btn am-btn-secondary'>
        添加到服务器</button></td>
  </tr>";
}
} 
}

//复制文件夹
function recurse_copy($src,$dst) { 
        $dir = opendir($src);
        @mkdir($dst);
        while(false !== ( $file = readdir($dir)) ) {
            if (( $file != '.' ) && ( $file != '..' )) {
                if ( is_dir($src . '/' . $file) ) {
                    recurse_copy($src . '/' . $file,$dst . '/' . $file);
                }
                else {
                    copy($src . '/' . $file,$dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    }
//模组列表
function pmod($path){
$file=glob($path."/*",GLOB_BRACE);
if(count($file)){
foreach($file as $afile){	
   echo "<tr><td><a href='";
$pa=str_replace("\\","/",$path);
$pat=str_replace("\\","/",PATHS);
$pa=str_replace($pat."/Servers/".$_COOKIE['ser']."/Workshop/Content/",'',$pa);
$afile=str_replace($path."/",'',$afile);
if(is_dir($path."/".$afile)){
    $p=$path."/".$afile;
    echo "manage.php?mod&p={$p}";
    $pa=$afile;
}else{
    echo "#";
    $pa=$pa."/".$afile;
}
echo "'>$afile</a></td><td></td><td><button type='button'  onclick=\"javascript:window.location.href='manage.php?mod&del={$pa}'\" class='am-btn am-btn-secondary'>
        删除文件</button></td>
  </tr>"; 
}
  }else{
      echo "<tr>";
echo "<td></td><td>无MOD可管理</td><td></td>
  </tr>"; 
}
}

//删除模组目录[没把上面的组合起来是因为一些特殊原因,有时间的话我会改的]
function deldir($dir) {
  $dh=opendir($dir);
  while ($file=readdir($dh)) {
    if($file!="." && $file!="..") {
      $fullpath=$dir."/".$file;
      if(!is_dir($fullpath)) {
          unlink($fullpath);
      } else {
          deldir($fullpath);
      }
    }
  }
  closedir($dh);
  if(rmdir($dir)) {
    return true;
  } else {
    return false;
  }
}
        /*
        Ucon 2.0 manage core
        Power by 7gugu
        */
?>