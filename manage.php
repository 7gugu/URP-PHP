<?php 
require 'function/corestart.php';
$v="m";
checkuser();
//首次进入
if(isset($_GET['ser'])){
	$ser=$_GET['ser'];
	setcookie('ser',$ser,time()+3600);
	echo "<script>location.href='manage.php?index';</script>"; 
	exit();
}
//启动服务器
if(isset($_GET['start'])){
	$ser=$_COOKIE['ser'];
	manage($ser,"start");
	exit();
}
//关闭服务器
if(isset($_GET['stop'])){
	$ser=$_COOKIE['ser'];
	manage($ser,"shutdown");
	exit();
}
//重启服务器
if(isset($_GET['restart'])){
	$ser=$_COOKIE['ser'];
	manage($ser,"restart");
	exit();
}
//实时状态
if(isset($_POST['players'])){
	$ser=$_COOKIE['ser'];
	udfile($ser,"players",$_POST['players'],"Server//Commands.dat");
	echo "<script>location.href='manage.php?index&suc=4';</script>";  
	exit();
}
if(isset($_POST['servername'])){
	$ser=$_COOKIE['ser'];
	udfile($ser,"servername",$_POST['servername'],"Server//Commands.dat");
	udfile($ser,"welcome",$_POST['welcome'],"Server//Commands.dat");
	udfile($ser,"difficult",$_POST['difficult'],"Server//Commands.dat");
	udfile($ser,"mode",$_POST['mode'],"Server//Commands.dat");
	udfile($ser,"map",$_POST['map'],"Server//Commands.dat");
	udfile($ser,"password",$_POST['password'],"Server//Commands.dat");
	udfile($ser,"view",$_POST['view'],"Server//Commands.dat");
	udfile($ser,"cheat",$_POST['cheat'],"Server//Commands.dat");
	echo "<script>location.href='manage.php?information&suc=4';</script>";  
	exit();
}
//获取数据
if(isset($_COOKIE['ser'])){
	if(isset($_SESSION['sec'])&&$_SESSION['sec']==1){
		$sid=$_COOKIE['ser'];
	$rs=query("select * from server where sid='{$sid}'");
	$row=mysqli_fetch_array($rs);
	}else{
	$sid=$_COOKIE['ser'];
	$username=$_SESSION['username'];
	$rs=query("select * from server where sid='{$sid}' and user ='{$username}'");
	$row=mysqli_fetch_array($rs);
	}
}else{
	echo "<script>location.href='list.php?err=4';</script>";  
	exit();
}
//简易控制台
if(isset($_POST['command'])){
	$command=$_POST['command'];
	//$command=@iconv('GB2312', 'UTF-8//IGNORE', $command); 
	//$command=@iconv('GBK', 'UTF-8//IGNORE', $command); 
	$sid=$_COOKIE['ser'];
	//echo strpos($command,'shutdown');
	if(strpos($command,'shutdown')!=''){
		query("update server set state='0'where sid='{$sid}'");	
	}
	$row=mysqli_fetch_array(query("select * from server where sid='{$sid}'"));
	rcon($command,1,$row['rport'],$row['rpw']);
	//echo gettype(rcon($command,1,$row['rport'],$row['rpw']));
	echo "<script>location.href='manage.php?order&suc=5';</script>";  
	exit();
}
//添加插件
if(isset($_GET['shop'])&&isset($_GET['move'])){
	$ser=$_COOKIE['ser'];
	if(file_exists($_GET['move'])){
		$move=$_GET['move'];
		$d=dirname(__FILE__)."/plugins/";
			$pl=str_replace("\\","/",$d);
		$plugin=str_replace($pl,"",$move);
	copy($move,PATHS."/Servers/$ser/Rocket/plugins/$plugin");
		echo "<script>location.href='manage.php?shop&suc=6';</script>"; 	
	exit();
}else{
		echo "<script>location.href='manage.php?shop&err=5';</script>";  
	exit();
}
}
//上传地图
if(isset($_GET['file'])&&isset($_FILES['upfile'])){
	
		$sid=$_COOKIE['ser'];
		$rem=upmap($_FILES['upfile']);
		$rez=getzip(PATHS.'/Servers/'.$sid.'/Workshop/Maps/'.$_FILES['upfile']['name'],PATHS.'/Servers/'.$sid.'/Workshop/Maps/');
		if($rez==true&&$rem==true){	
		header("Location:manage.php?map&suc=7");//upload successfully
		}else{
		header("Location:manage.php?map&err=6");//upload faild
	}
}
//mod
if(isset($_GET['mod'])){
	if(isset($_GET['del'])){
		if(is_dir(PATHS."/Servers/".$_COOKIE['ser']."/Workshop/Content/".$_GET['del'])){
			deldir(PATHS."/Servers/".$_COOKIE['ser']."/Workshop/Content/".$_GET['del']);
		}else{
			unlink(PATHS."/Servers/".$_COOKIE['ser']."/Workshop/Content/".$_GET['del']);
		}
		header("Location:manage.php?mod&suc=24");
	}
	if(isset($_FILES['upfile'])){
		set_time_limit(0);
		$sid=$_COOKIE['ser'];
		$rem=upmod($_FILES['upfile']);
		$rez=getzip(PATHS.'/Servers/'.$sid.'/Workshop/Content/'.$_FILES['upfile']['name'],PATHS.'/Servers/'.$sid.'/Workshop/Content/');
		unlink(PATHS.'/Servers/'.$sid.'/Workshop/Content/'.$_FILES['upfile']['name']);
		if($rez==true&&$rem==true){	
		header("Location:manage.php?mod&suc=22");//upload successfully
		}else{
		header("Location:manage.php?mod&err=23");//upload faild
	}
}
}
//ajax read
if(isset($_GET['read'])){
 $ser=$_GET['read'];
$file = fopen(PATHS."\Servers\\$ser\\Rocket\\Logs\\Rocket.log", "r") or exit("打开log文件失败,请联系管理员!");
while(!feof($file))
{
 $rs=fgets($file);
 $rs=trim($rs);
 echo $rs. "<br />";
}
fclose($file);
exit();
}
?>
<!doctype html>
<html class="no-js fixed-layout">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>URP | 管理服务器</title>
  <meta name="description" content="manage">
  <meta name="keywords" content="index">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="renderer" content="webkit">
  <meta http-equiv="Cache-Control" content="no-siteapp" />
  <link rel="icon" type="image/png" href="/i/favicon.png">
  <link rel="apple-touch-icon-precomposed" href="/i/app-icon72x72@2x.png">
  <meta name="apple-mobile-web-app-title" content="Amaze UI" />
  <link rel="stylesheet" href="assets/css/amazeui.min.css"/>
  <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
 <!-- header start -->
<?php require 'function/header.php';?>
  <!-- header end -->

<div class="am-cf admin-main">
  <!-- sidebar start -->
  <?php require 'function/sidebar.php';?>
  <!-- sidebar end -->

  <!-- content start -->
  <div class="admin-content">
  <div class="admin-content-body">
    <div class="am-cf am-padding am-padding-bottom-0">
      <div class="am-fl am-cf">
        <strong class="am-text-primary am-text-lg">管理服务器</strong> /
        <small>
		<?php 
		echo $row['name'] ;
		?>
		</small>
      </div>
    </div>
    <hr>
	<div class="am-u-sm-12">
<?php if(isset($_GET['err'])){
msg($_GET['err']);
	}
	if(isset($_GET['suc'])){	
msg($_GET['suc'],1);
 }?>
	</div>
<?php
	if($row!=false){
	if(isset($_GET['index'])){
		$on="";
		$off="";
		$sstate=$row['state'];
		$state="未知";
		if($sstate==1){
		$on="disabled";
		$state="在线";
		}else{
			$off="disabled";
			$state="离线";
		}
echo "<table class='am-table am-table-striped '>
 <thead>
        <tr>
            <th>服务器基础设置</th>
            <th></th>
            <th></th>
        </tr>
    </thead>
	
    <tbody>
        <tr>
            <td>操作服务器</td>
            <td>
			<div class='am-u-lg-8'>
			";
echo "
			<input type='submit'  onclick=\"javascript:this.disabled=true;this.value='启动中·.....';window.location.href='manage.php?start';\" class='am-btn am-btn-success ' value='启动服务器'{$on} ></input>
            <button type='submit' onclick=\"javascript:this.disabled=true;this.value='关闭中·.....';window.location.href='manage.php?stop'\" class='am-btn am-btn-danger'{$off}>关闭服务器</button> 
            <button type='submit' onclick=\"javascript:this.disabled=true;this.value='重启中·.....';window.location.href='manage.php?restart'\" class='am-btn am-btn-warning'{$off}>重启服务器</button> 			
			";
			echo "
			</div>
			</td>
			<td>
			</td>
        </tr>
		<form action='manage.php' method='POST'>
        <tr>
            <td>服务器最大人数</td>
            <td>
			<div class='am-u-lg-6'>
			<input id='players' name='players' type='text' class='am-form-field' value='{$row['players']}'>
			</div>
			</td>
			<td>
			</td>
        </tr>
		<tr>
            <td>Rcon密码</td>
            <td>
			<div class='am-u-lg-6'>
			<input  type='text' class='am-form-field' value='{$row['rpw']}' >
			</div>
			</td>
			<td>
			</td>
        </tr>
        <tr>
            <td>可用时间</td>
            <td>
			<div class='am-u-lg-6'>
			{$row['time']}
			</div>
			</td>
			<td>
			</td>
        </tr>
        
		<tr>
            <td>服务器状态</td>
            <td>
			<div class='am-u-lg-6'>
			{$state}
			</div>
			</td>
			<td>
			</td>
        </tr>
		<tr>
            <td>游戏端口</td>
            <td>
			<div class='am-u-lg-6'>
			<input id='port' name='port' type='text' class='am-form-field' value='{$row['port']}' disabled>
			</div>
			</td>
			<td>
			</td>
        </tr>
        <tr>
            <td>Rcon端口</td>
            <td>
			<div class='am-u-lg-6'>
			<input id='rport' name='rport' type='text' class='am-form-field' value='{$row['rport']}' disabled>
			</div>
			</td>
			<td>
			</td>
        </tr>
		<tr>
            <td>服务器IP</td>
            <td>
			<div class='am-u-lg-6'>
			<input id='sip' name='sip' type='text' class='am-form-field' value='";
			echo IP;
			echo "' disabled>
			</div>
			</td>
			<td>
			</td>
			
        </tr>
<tr>		
<td>
			</td>
			<td>	
			<div class='am-u-lg-6'>
			
		<button type='submit' class='am-btn am-btn-success'>保存设置</button>
		</div>
		</td>
		<td>
			</td>
		</tr>
    </tbody>
	</form>
</table>
	";}
if(isset($_GET['information'])){
	echo "
<table class='am-table am-table-striped '>
 <thead>
        <tr>
            <th>服务器详细参数设定</th>
            <th></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
	<form action='manage.php' class='am-form' method='POST'>
        <tr>
            <td>服务器名称</td>
            <td>
			<div class='am-u-lg-6'>
			<input id='servername' name='servername' type='text' class='am-form-field' value='{$row['name']}'>
			</div>
			</td>
			<td>
			</td>
        </tr>
        <tr>
            <td>服务器欢迎信息</td>
            <td>
			<div class='am-u-lg-6'>
			<textarea name='welcome' id='welcome'class='' rows='5' id='doc-ta-1' >{$row['welcome']}</textarea>
			</div>
			</td>
			<td>
			</td>
        </tr>
        <tr>
            <td>游戏难度</td>
            <td>
			<div class='am-u-lg-6'>
			 <select name='difficult' id='doc-select-1'>";
			 if($row['difficult']=='normal'){
				 echo"
          <option value='normal'>Normal</option>
          <option value='easy'>Easy</option>
		 <option value='hard'>Difficult</option>
		    <option value='gold'>Gold</option>";
			 }elseif($row['difficult']=='easy'){
				 echo"
				 <option value='easy'>Easy</option>
          <option value='normal'>Normal</option>
		 <option value='hard'>Difficult</option>
		    <option value='gold'>Gold</option>";
			 }elseif($row['difficult']=='hard'){
				 echo"
			<option value='hard'>Difficult</option>
          <option value='normal'>Normal</option>
          <option value='easy'>Easy</option>
		    <option value='gold'>Gold</option>";
			 }elseif($row['difficult']=='gold'){
				echo"
				<option value='gold'>Gold</option>
          <option value='normal'>Normal</option>
          <option value='easy'>Easy</option>
		   <option value='hard'>Difficult</option>"; 
			 }
			echo"
        </select>
			</div>
			</td>
			<td>
			</td>
        </tr>
        
		<tr>
            <td>模式</td>
            <td>
			<div class='am-u-lg-6'>
			 <select name='mode' id='doc-select-1'>";
			 if($row['mode']=='pvp'){
				 echo"<option value='pvp'>PVP</option>
          <option value='pve'>PVE</option>
";
			 }elseif($row['mode']=='pve'){
				 echo"
				 <option value='pvp'>PVE</option>
          <option value='pve'>PVP</option>";
			 }
       echo " </select>
			</div>
			</td>
			<td>
			</td>
        </tr>
		<tr>
            <td>地图 [{$row['map']}]</td>
            <td>
			<div class='am-u-lg-6'>
			 <select name='map' id='doc-select-1'>
			
			 
			 ";
		  gfl(0);
		  gfl(1);
       echo " </select>
		</div>
			</td>
			<td>
			</td>
        </tr>
        <tr>
            <td>密码</td>
            <td>
			<div class='am-u-lg-6'>
			<input id='password' name='password' value='{$row['password']}' type='text' class='am-form-field' >
			</div>
			</td>
			<td>
			</td>
        </tr>
		<tr>
            <td>视角</td>
            <td>
			<div class='am-u-lg-6'>
	 <select name='view' id='doc-select-1'>";
	  if($row['view']=='both'){
				 echo"
				 <option value='both'>both</option>
          <option value='first'>first</option>
		  <option value='third'>third</option>
          ";
			 }elseif($row['view']=='first'){
				 echo"
				 <option value='first'>first</option>
				 <option value='both'>both</option>
		  <option value='third'>third</option>
				 ";
			 }elseif($row['view']=='third'){
				 echo"
				  <option value='third'>third</option>
				 <option value='both'>both</option>
          <option value='first'>first</option>
				";
			 }
      echo "  </select>
			</div>
			</td>
			<td>
			</td>
        </tr>	
		<tr>
            <td>作弊</td>
            <td>
			<div class='am-u-lg-6'>
	 <select name='cheat' id='doc-select-1'>";
		   if($row['cheat']=='1'){
				 echo"<option value='1'>开启</option>
				 <option value='0'>关闭</option>
";
			 }elseif($row['cheat']=='0'){
				 echo"
				 <option value='0'>关闭</option>
          <option value='1'>开启</option>";
			 }
       echo " </select>
			</div>
			</td>
			<td>
			</td>
        </tr>
		<tr>
		<td>
			</td>
		<td>
		<div class='am-u-lg-6'>
		<button type='submit' class='am-btn am-btn-success'>保存设置</button>
		</div>
		</td>
		<td>
			</td>
		</tr>
		</form>
    </tbody>
</table>
	
	";}
if(isset($_GET['order'])){
	$dis='';
	if($row['state']==0){
		$dis='disabled';
	}
	echo "

	  <form class='am-form' ";
	  if($row['state']==1){
		echo "action='manage.php'";
	} 
	echo " method='POST'  >
<div class='am-form-group'>
<script language='javascript'> 
var xmlHttp; 
function createXMLHttpRequest(){ 
if(window.ActiveXObject){ 
xmlHttp = new ActiveXObject('microsoft.XMLHTTP'); 
} 
else if(window.XMLHttpRequest){ 
xmlHttp = new XMLHttpRequest(); 
} 

} 
function sendRequest(){ 
createXMLHttpRequest(); 
var name = '{$_COOKIE['ser']}'; 
url = 'manage.php?read='+name; 
xmlHttp.onreadystatechange = callback; 
xmlHttp.open('GET',url,true); 
xmlHttp.send(null); 
} 
function callback(){ 
if(xmlHttp.readyState == 4){ 
if(xmlHttp.status == 200){ 
document.getElementById('showcommand').innerHTML = xmlHttp.responseText; 
document.getElementById('showcommand').scrollTop=document.getElementById('showcommand').scrollHeight;
} 
} 
} 
window.onload=sendRequest();
</script>
			<div class='am-u-sm-12 '>
      <label for='doc-ipt-file-1'>命令行</label>
	   <pre id='showcommand' class='am-pre-scrollable'>
	   </pre>
      <input id='command' name='command' type='text' onkeydown='sendRequest()' id='doc-ipt-file-1' >
	       <p class='am-form-help'>我们推荐您使用Windows自带的Telnet来连接服务器,此处仅仅是提供一个入口给大家临时使用</p>
           <button type='submit' class='am-btn am-btn-success' {$dis}>发送</button>   
	   
	  </div>
	  <div class='am-u-sm-4 '>
	  </div>
    </div>
          </form>
		  <br><br> <br><br> <br><br>
	
	";
}
if(isset($_GET['plugin'])){
	echo "
	
<table class='am-table am-table-striped '>
 <thead>
        <tr>
            <th>服务器插件设置</th>
            <th></th>
            <th>操作:</th>
			<th></th>
        </tr>
    </thead>
    <tbody>
       
           ";
		   $ser=$_COOKIE['ser'];
		   $pa=PATHS."\Servers\\".$ser."\Rocket\Permissions.config.xml";
		   		      echo "<tr><td></td>";
  echo "<td><strong><font color='red'>权限组管理</font></strong></td>";
  echo "<td><a href='manage.php?per&pfile=".$pa."' >编辑</a>          
  </td><td></td></tr>";
		   plist(PATHS."/Servers/$ser/Rocket/plugins","dll");
		  echo  "
      
		
<tr>		
<td>
			</td>
			<td>	
		</td>
		<td>
			</td>
			<td>
		<button type='button'  onclick=\"javascript:window.location.href='manage.php?shop'\" class='am-btn am-btn-secondary'>添加新的插件</button>
		</td>
		</tr>
    </tbody>
</table>";
}
if(isset($_GET['po'])){
	$po=$_GET['po'];
	$ser=$_COOKIE['ser'];
	if(isset($_GET['del'])){
		echo "
		<table class='am-table am-table-striped am-table-centered'>
 <thead>
        <tr>
            <th>服务器插件设置</th>
        </tr>
    </thead>
	<tbody>
	<tr>
	<td>";
	
	echo del(PATHS."/Servers/$ser/Rocket/plugins/".$po); 
	echo "<br><a href='manage.php?plugin'>返回插件列表</a></td>
	</tr>
	</tbody>
	</table>";
		exit();
	}
	echo "
<table class='am-table am-table-striped '>
 <thead>
        <tr>
            <th>服务器插件设置</th>
            <th></th>
            <th>操作:</th>
			<th></th>
        </tr>
    </thead>
	<form>
    <tbody>
       <tr>
	   <td><a href=\"javascript:history.go(-1);\">返回上一级</a></td>
	   <td></td>
	   <td></td>
	   <td></td>
	   </tr>
           ";
	plist(PATHS."/Servers/$ser/Rocket/plugins/".$po,"xml");
	 echo  "
      
		
<tr>		
<td>
			</td>
			<td>	
			
		</td>
		<td>
			</td><td></td>
		</tr>
    </tbody>
	</form>
</table>";
}
if(isset($_GET['pfile'])){
	if(isset($_GET['per'])){
		$fn[1]="Permissions.config.xml";
	}else{
	$ser=$_COOKIE['ser'];
	$fn=str_replace(PATHS."/Servers/$ser/Rocket/plugins/","",$_GET['pfile']);
	$fn=explode("/",$fn);
	}
		echo "
<table class='am-table am-table-striped '>
 <thead>
        <tr>
            <th>服务器插件设置</th>
			<th></th>
		
        </tr>
    </thead>
	<form action='manage.php?save' method='POST' id='save'>
    <tbody>
       <tr>
	   <td> <a href=\"javascript:history.go(-1);\"> 返回上一级</a></td>
	   	   <td></td> <td></td>
	   </tr>
	   <tr><td>文件名:<br>{$fn[1]}<br></td>
	   <td>
           ";?>
		   <style type="text/css" media="screen">
  pre{
	  padding:30%;
	  hight:100%;
  }

  </style>
		     <script src="assets/ace/ace.js" type="text/javascript" charset="utf-8"></script>
			 <input type='hidden' value='<?php echo $_GET['pfile']; ?>' name='path' id='path'></input>
            <pre id="editor"><?php echo rwfile($_GET['pfile'],'r','');?></pre>
<input type="hidden" id="es" name='es' value=''/>
<script>
    var editorr = ace.edit("editor");

    editorr.getSession().setMode("ace/mode/xml");
	   editorr.getSession().setWrapLimitRange(null, null);
   // editorr.getSession().setUseWrapMode(true);
    //不显示垂直衬线
    editorr.renderer.setShowPrintMargin(false);
	
	editorr.focus();
	function get(){
		document.getElementById("es").value=editorr.getValue();
		document.getElementById('save').submit();
	}
</script>

	<?php  echo  "
      </td><td></td>
	</tr>
		
<tr>		
<td>
			</td>
			<td>	
			<div class='am-u-lg-6'>
		<button type='button' class='am-btn am-btn-success' onclick='get()'>保存设置</button>
		</div>
		</td>
		 <td></td>
		</tr>
    </tbody>
	</form>
</table>";
}
if(isset($_GET['save'])&&isset($_POST['path'])){
	$path=$_POST['path'];
	$text=$_POST['es']; 
	if(rwfile($path,'w',$text)){
		echo "
		<table class='am-table am-table-striped am-table-centered'>
 <thead>
        <tr>
            <th>服务器插件设置</th>
        </tr>
    </thead>
	<tbody>
	<tr>
	<td>保存成功!<br>你可以<a href='manage.php?plugin'>返回文件列表</a></td>
	</tr>
	</tbody>
	</table>
	";
	}else{
			echo "
		<table class='am-table am-table-striped am-table-centered'>
 <thead>
        <tr>
            <th>服务器插件设置</th>
        </tr>
    </thead>
	<tbody>
	<tr>
	<td>保存失败!<br>你可以<a href='manage.php?plugin'>返回文件列表</a></td>
	</tr>
	</tbody>
	</table>
	";
	}
}
if(isset($_GET['shop'])){
	echo "
	
	<table class='am-table am-table-bordered'>
    <thead>
        <tr>
            <th>插件名称</th>
            <th>描述</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
	<tr>
	<td><a href='manage.php?plugin'>返回文件列表</a></td><td></td><td></td>
	</tr>
	";
$fp=str_replace("\\","/",dirname(__FILE__));
	pshop($fp."/plugins/");
	echo "
    </tbody>
</table>
	";
	
}
if(isset($_GET['map'])){
	echo "
	<div class='am-u-sm-12'>
	<section class='am-panel am-panel-default'>
  <header class='am-panel-hd'>
    <h3 class='am-panel-title'>上传地图</h3>
  </header>
  <div class='am-panel-bd'>
     <form class='am-form' action='manage.php?file' enctype='multipart/form-data' method='post'>
      <input type='file' id='doc-ipt-file-1' name='upfile'>
      <p class='am-form-help'>请把地图文件压缩成ZIP格式,上传后请到设置选项,更改设置</p>
	   <button type='submit' class='am-btn am-btn-warning'>上传</button>
          </form>
  </div>
</section></div>";

}
if(isset($_GET['log'])){
	echo "
	<div class='am-g'> 
        <div class='am-u-sm-8'>
    <legend>日志查询</legend>
          </div>
        </div>
 <div class='am-u-sm-12'>
 <h4><strong>日志为最新日志,若需全部日志请联系管理员</strong><h4>
 <pre class='am-pre-scrollable'>
 ";
 $ser=$_COOKIE['ser'];
$file = fopen(PATHS."\Servers\\$ser\\Rocket\\Logs\\Rocket.log", "r") or exit("打开log文件失败,请联系管理员!");
while(!feof($file))
{
 $rs=fgets($file);
/* $rs = str_replace ( "[Exception] Rocket.CoreException in Rocket.Core: System.IO.IOException: Write failure ---> System.Net.Sockets.SocketException: 您的主机中的软件中止了一个已建立的连接。", "Error!", $rs ); 
 $rs=str_replace ("at System.Net.Sockets.Socket.Send (System.Byte[] buf, Int32 offset, Int32 size, SocketFlags flags) [0x00000] in <filename unknown>:0","",$rs);
 $rs=str_replace ("at System.Net.Sockets.NetworkStream.Write (System.Byte[] buffer, Int32 offset, Int32 size) [0x00000] in <filename unknown>:0 ","",$rs);
 $rs=str_replace ("  --- End of inner exception stack trace ---","",$rs);
 $rs=str_replace (" at System.Net.Sockets.NetworkStream.Write (System.Byte[] buffer, Int32 offset, Int32 size) [0x00000] in <filename unknown>:0 ","",$rs);
 $rs=str_replace ("at Rocket.Core.RCON.RCONServer.Send (System.Net.Sockets.TcpClient client, System.String text) [0x00000] in <filename unknown>:0","",$rs);
 $rs=str_replace (" at Rocket.Core.RCON.RCONConnection.Send (System.String command, Boolean nonewline) [0x00000] in <filename unknown>:0 ","",$rs);
 $rs=str_replace ("  at Rocket.Core.RCON.RCONServer.handleConnection (System.Object obj) [0x00000] in <filename unknown>:0 ","",$rs);*/
 $rs=trim($rs);
 echo $rs. "<br />";
}
fclose($file);
echo "
</pre>
		  </div>";  
}
	if(isset($_GET['players'])){
		echo "
	<div class='am-g'> 
        <div class='am-u-sm-8'>
    <legend>在线玩家</legend>
          </div>
        </div>
 <div class='am-u-sm-12'>
 
 <h2><strong>在线玩家:";
 players();
 echo "</strong><h2><hr></div>
 ";
	}	
	if(isset($_GET['mod'])){
	echo "
	<div class='am-u-sm-12'>
	<section class='am-panel am-panel-default'>
  <header class='am-panel-hd'>
    <h3 class='am-panel-title'>上传MOD</h3>
  </header>
  <div class='am-panel-bd'>
     <form class='am-form' action='manage.php?mod' enctype='multipart/form-data' method='post'>
      <input type='file' id='doc-ipt-file-1' name='upfile'>
      <p class='am-form-help'>请把MOD文件压缩成ZIP格式</p>
	   <button type='submit' class='am-btn am-btn-warning'>上传</button>
          </form>
  </div>
</section>
<hr>
<table class='am-table am-table-bordered'>
    <thead>
        <tr>
            <th>文件名</th>
            <th></th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
	";
	$sid=$_COOKIE['ser'];
	if(isset($_GET['p'])){
		$p=$_GET['p'];
		echo "<td><a href=\"javascript:history.go(-1);\">返回上一层</a></td><td></td><td></td>";
	    pmod($p);
	}else{
	pmod(PATHS."\Servers\\$sid\Workshop\Content");
	}
	echo "
    </tbody>
</table>
</div>";

}
}else{
	echo "<script>location.href='list.php?err1';</script>";  
	exit();
}


?>
</div>
<footer class="admin-content-footer">
      <hr>
      <p class="am-padding-left">© 2016 Power By 7gugu.</p>
    </footer>
</div>
  <!-- content end -->
</div>

<a href="#" class="am-icon-btn am-icon-th-list am-show-sm-only admin-menu" data-am-offcanvas="{target: '#admin-offcanvas'}"></a>

<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/amazeui.min.js"></script>
<script src="assets/js/app.js"></script>
</body>
</html>
