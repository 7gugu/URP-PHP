<?php 
if(isset($_GET['i'])&&isset($_POST['gamepos'])){
	define("PATHS",$_POST['gamepos']."/Unturned");
	define('SYSTEM_ROOT',dirname(dirname(__FILE__)));
	define('DBIP',$_POST['dbip']);
	define('DBUSERNAME',$_POST['dbusername']);
	define('DBPASSWORD',$_POST['dbpassword']);
	define('DBNAME',$_POST['dbname']);
		$sql  = str_ireplace("define(\"PATHS\",\"game\");","define(\"PATHS\",\"{$_POST['gamepos']}\".\"\Unturned\");", file_get_contents(SYSTEM_ROOT.'/config/config.php'));
		$sql  = str_ireplace("define(\"DBIP\",\"localhost\");","define(\"DBIP\",\"{$_POST['dbip']}\");",$sql); 
		$sql  = str_ireplace("define(\"DBUSERNAME\",\"root\");","define(\"DBUSERNAME\",\"{$_POST['dbusername']}\");",$sql); 
		$sql  = str_ireplace("define(\"DBPASSWORD\",\"root\");","define(\"DBPASSWORD\",\"{$_POST['dbpassword']}\");",$sql); 
		$sql  = str_ireplace("define(\"DBNAME\",\"urp\");","define(\"DBNAME\",\"{$_POST['dbname']}\");",$sql); 
		file_put_contents(SYSTEM_ROOT.'/config/config.php',$sql);
		$connect=mysqli_connect(DBIP,DBUSERNAME,DBPASSWORD,DBNAME) or die(header("Location: install.php?step3&err=1"));
 function query($text){
	global $connect;
	mysqli_query($connect,"set names 'utf8'");
	$res=mysqli_query($connect,"{$text}");
	return $res;
}
function loadsql($file){
$_sql = file_get_contents($file);
$_arr = explode(';', $_sql);
foreach ($_arr as $_value) {
    query($_value.';');
}
}
    loadsql("cron.sql");
	loadsql("user.sql");
	loadsql("inser.sql");
	loadsql("server.sql");
	loadsql("notice.sql");
	loadsql("plugin.sql");
	sleep(1);
$uid = query("select * from user order by id DESC limit 1 ");
	$uid=mysqli_fetch_array($uid);
	if($uid){
		$uid=$uid['id'];
	}else{
	$uid=0;
	}
	$uid++;	
query("insert into user(id,username,password,email,admin)values('{$uid}','{$_POST['username']}','{$_POST['password']}','{$_POST['email']}','1')");
$nums=mysqli_affected_rows($connect);
	if($nums){
copy(dirname(__FILE__)."/start.exe",PATHS."/start.exe");
header("Location: install.php?step4");
	}else{
header("Location: install.php?step3&err=2");
	}
exit();
}

?>
<!doctype html>
<html class="no-js fixed-layout">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>URP | 安装向导</title>
  <meta name="description" content="安装">
  <meta name="keywords" content="list">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="renderer" content="webkit">
  <meta http-equiv="Cache-Control" content="no-siteapp" />
  <link rel="icon" type="image/png" href="/i/favicon.png">
  <link rel="apple-touch-icon-precomposed" href="/i/app-icon72x72@2x.png">
  <meta name="apple-mobile-web-app-title" content="URP start" />
  <link rel="stylesheet" href="../assets/css/amazeui.min.css"/>
  <link rel="stylesheet" href="../assets/css/admin.css">
 
</head>
<body>
 <!-- header start -->
 <header class='am-topbar am-topbar-inverse admin-header'>
  <div class='am-topbar-brand'>
    <strong>URP</strong> <small>后台管理</small>
  </div>

  <div class='am-collapse am-topbar-collapse' id='topbar-collapse'>
  </div>
</header>
  <!-- header end -->
  
<div class="am-cf admin-main">
  <!-- sidebar start -->
<div class='admin-sidebar am-offcanvas' id='admin-offcanvas'>
    <div class='am-offcanvas-bar admin-offcanvas-bar'>
      <ul class='am-list admin-sidebar-list'>
        <li><a href='#'><span class='am-icon-th-large'></span> 阅读协议</a></li>
        <li><a href='#'><span class='am-icon-refresh'></span> 功能检测</a></li>
	    <li><a href='#'><span class='am-icon-list'></span> 数据配置</a></li>
		 <li><a href='#'><span class='am-icon-bolt'></span> 后端安装</a></li>
        <li><a href='#'><span class='am-icon-check-square'></span> 安装完成</a></li>
      </ul>  
	   <div class='am-panel am-panel-default admin-sidebar-panel'>
        <div class='am-panel-bd'>
          <p><span class='am-icon-bookmark'></span> 公告</p>
          <p>我才是帅比—— 7gugu</p>
        </div>
      </div>
    </div>
  </div>
  <!-- sidebar end -->
  <!-- content start -->
  <?php 
  if(file_exists(dirname(dirname(__FILE__))."/assets/install.lock"))
{
	echo "<br><div class='am-u-sm-8'><div class='am-alert am-alert-danger'>";
    echo "<h3>检测到Install.lock文件,请删除后再继续安装!</h3>
	<li>若你已安装此程序,点击<a href='../index.php'><code>此处</code></a>返回首页</li>
	";
	echo "</div></div>";
    exit();
}
  function checkfunc($f,$m = false) {
	if (function_exists($f)) {
		return '<font color="green">可用</font>';
	} else {
		if ($m == false) {
			return '<font color="black">不支持</font>';
		} else {
			return '<font color="red">不支持</font>';
		}
	}
}
function checkclass($f,$m = false) {
	if (class_exists($f)) {
		return '<font color="green">可用</font>';
	} else {
		if ($m == false) {
			return '<font color="black">不支持</font>';
		} else {
			return '<font color="red">不支持</font>';
		}
	}
}

  ?>
   <div class="admin-content">
    <div class="admin-content-body">
      <div class="am-cf am-padding am-padding-bottom-0">
        <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">安装流程</strong> / <small>Insatll</small></div>
      </div>
      <hr>
      <div class="am-g">
        <div class="am-u-sm-12">
		<?php if(isset($_GET['err'])){?>
		<div class="am-alert am-alert-danger">
		<?php
$err=$_GET['err'];		
		if($err==1){
			echo "数据库连接失败,请检查信息是否填写正确";
		}elseif($err==2){
			echo "数据导入失败,请检查信息是否填写正确";
		}elseif($err=3){
				echo "Install.lock生成失败,请手动在assets文件夹中创建";
		}
		?>
</div>
		<?php }?>
		<?php 
		if(isset($_GET['step2'])){
		?>
		  <h1 class='am-article-title'>检测环境</h1>
		   <table class="am-table am-table-striped am-table-hover table-main">
              <thead>
              <tr>
               <th class="table-id">功能</th><th class="table-title">需求</th><th class="table-type">当前</th><th class="table-author am-hide-sm-only">用途</th>
              </tr>
              </thead>
              <tbody>       
	<tr>
			<td>cURL: curl_exec()</td>
			<td>推荐</td>
			<td><?php echo checkfunc('curl_exec'); ?></td>
			<td>抓取网页</td>
		</tr>
		<tr>
			<td>file_get_contents()</td>
			<td>必须</td>
			<td><?php echo checkfunc('file_get_contents',true); ?></td>
			<td>读取文件</td>
		</tr>
		<tr>
			<td>Socket: fsockopen()</td>
			<td>必须</td>
			<td><?php echo checkfunc('fsockopen',true); ?></td>
			<td>Socket，例如模拟多线程签到</td>
		</tr>
		<tr>
			<td>Zip</td>
			<td>必须</td>
			<td><?php echo checkfunc('zip_open',true); ?></td>
			<td>Zip 解包和压缩</td>
		</tr>
		<tr>
			<td>写入权限</td>
			<td>必须</td>
			<td><?php if (is_writable("install.php")) { echo '<font color="green">可用</font>'; } else { echo '<font color="black">不支持</font>'; } ?></td>
			<td>写入文件(1/2)</td>
		</tr>
		<tr>
			<td>file_put_contents()</td>
			<td>必须</td>
			<td><?php echo checkfunc('file_put_contents',true); ?></td>
			<td>写入文件(2/2)</td>
		</tr>
		<tr>
			<td>MySQL: mysql_connect()</td>
			<td>必须</td>
			<td><?php echo checkfunc('mysql_connect',true); ?></td>
			<td>数据库操作，若支持 MySQLi 可忽略本项</td>
		</tr>
		<tr>
			<td>MySQLi: mysqli</td>
			<td>必须</td>
			<td><?php echo checkclass('mysqli'); ?></td>
			<td>数据库操作，若支持本项可忽略不支持 MySQL 函数</td>
		</tr>
		<tr>
			<td>PHP 5+</td>
			<td>必须</td>
			<td><?php echo phpversion(); ?></td>
			<td>核心,未来URP可能不支持PHP 5.3以下版本</td>
		</tr>
              </tbody>  
            </table>
			 <button class='am-btn am-btn-success'type='button' onclick="javascript:window.location.href='install.php?step3'">下一步>></button>
			 <br><br> 
		<?php }elseif(isset($_GET['step3'])){?>
		<h1 class='am-article-title'>配置数据</h1>
		<form class="am-form" method="POST" action="install.php?i" >
		<div class="am-input-group">
  <span class="am-input-group-label">数据库地址</span>
  <input type="text" name="dbip" class="am-form-field" value="localhost" placeholder="数据库地址">
</div><br>
		<div class="am-input-group">
  <span class="am-input-group-label">数据库用户名</span>
  <input type="text" name="dbusername" class="am-form-field" placeholder="数据库用户名">
</div><br>
<div class="am-input-group">
 <span class="am-input-group-label">数据库密码</span>
  <input type="text" name="dbpassword" class="am-form-field" placeholder="数据库密码">
    </div><br>
	<div class="am-input-group">
	<span class="am-input-group-label">数据库名</span>
  <input type="text" name="dbname" class="am-form-field" placeholder="数据库名">
  </div>
  <hr>
  <div class="am-input-group">
  <span class="am-input-group-label">Unturned文件夹位置</span>
  <input type="text" name="gamepos" class="am-form-field" placeholder="需要全路径">
   <span class="am-input-group-label">\Unturned</span>
		</div>
		<hr>
		  <div class="am-input-group">
  <span class="am-input-group-label">管理员用户名</span>
  <input type="text" name="username" class="am-form-field" placeholder="管理员用户名">
		</div><br>
		<div class="am-input-group">
  <span class="am-input-group-label">管理员密码</span>
  <input type="text" name="password" class="am-form-field" placeholder="管理员密码">
		</div><br>
		<div class="am-input-group">
  <span class="am-input-group-label">管理员邮箱</span>
  <input type="text" name="email" class="am-form-field" placeholder="管理员邮箱">
		</div><br>
		<button class='am-btn am-btn-success'type='submit'>下一步>></button>
		<br><br>
		</form>
		 <script>
	  function check(){
   if(document.getElementById("dbip").value=="")
    {
        alert("数据库IP不可为空!");
        document.getElementById("dbip").value.focus();
        return false;
     }
   if(document.getElementById("dbusername").value=="")
    {
        alert("数据库账号不可为空!");
       document.getElementById("dbusername").focus();
        return false;
     }
   if(document.getElementById("dbpassword").value=="")
    {
        alert("数据库密码不可为空!");
       document.getElementById("dbpassword").focus();
		return false;
     }
	  if(document.getElementById("dbname").value=="")
    {
        alert("数据库名不可为空!");
       document.getElementById("dbname").focus();
		return false;
     }
	  if(document.getElementById("gamepos").value=="")
    {
        alert("游戏路径不可为空!");
       document.getElementById("gamepos").focus();
		return false;
     }
	  if(document.getElementById("username").value=="")
    {
        alert("管理员账户不可为空!");
       document.getElementById("username").focus();
		return false;
     }
	  if(document.getElementById("password").value=="")
    {
        alert("管理员密码不可为空!");
       document.getElementById("password").focus();
		return false;
     }
	  if(document.getElementById("email").value=="")
    {
        alert("管理员邮箱不可为空!");
       document.getElementById("email").focus();
		return false;
     }
    return true;
	  }
	  </script>
		<?php }elseif(isset($_GET['step4'])){
		function socket(){
@set_time_limit(0);
$address = 'localhost';
$socket = @socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
$result = @socket_connect($socket, $address, 1935);
sleep(1);
$in="installtest";
@socket_write($socket, $in, strlen($in));
sleep(2);
$buf = @socket_read($socket,8192);
if($buf=="OK"){
	return true;
}else{
	return false;
}
@socket_close($socket);		
		}?>
			<h1 class='am-article-title'>安装后端</h1>
			<div class="am-g">
  <div class="am-u-sm-6 am-u-lg-centered">
  <div class=" am-alert am-alert-secondary" data-am-alert>
  <h3>注意:</h3>
  <p>Start.exe在安装完成后,需要一直开启,以此启动服务器</p>
  <ul>
    <li>在Unturned文件夹中找到Start.exe运行后,点击下方的验证,来继续下一步操作。</li>
    <li>若在Unturned文件夹中找不到Start.exe,可手动从install文件夹内复制start.exe到Unturned文件夹中,然后运行并启动验证。</li>
   <?php
  if(socket()){
	  
	  ?>
  <li><h3><font color="green">验证成功</font></h3></li>
  <button class='am-btn am-btn-success'type='button' onclick="javascript:window.location.href='install.php?step5'">下一步>></button>
  <?php }else{
	  echo "<li><h3><font color='red'>验证失败</font><h3></li>";
  ?>
  	    <button class='am-btn am-btn-secondary' type='button' onclick="javascript:window.location.href='install.php?step4&f'">验证>></button>
  <?php } ?>
  </ul>
</div>
  </div>
</div>
		<?php }elseif(isset($_GET['step5'])){
			fopen("../assets/install.lock", "w+") or die(header("Location: install.php?step5&err=3"));?>
			<h1 class='am-article-title'>安装完成</h1>
<div class="am-u-sm-6 am-u-lg-centered">
			<h3>恭喜你,安装已经完成,你现在可以把install目录删除,以防止黑客入侵<br><a href="../index.php">前往首页</a></h3>
</div>
			<?php	}else{ ?>
		<h1 class='am-article-title'>阅读许可协议</h1>
		<pre class="am-pre-scrollable">
  URP《最终用户使用许可协议书》 V1.0
        【首部及导言】
        架设或使用URP项目（包括此程序本身及相关所有文档，以下简称“URP”）及其衍生品（包括在URP基础上二次创作的项目及依赖URP程序运行的插件等），您应当阅读并遵守URP《最终用户使用许可协议书》（以下简称“协议”） 。请您务必审慎阅读、充分理解各条款内容，协议中的重点内容可能以加粗或加下划线的形式提示您重点注意。除非您已阅读并接受本协议所有条款，否则您无权架设或使用URP。您架设或使用URP即视为您已阅读并同意本协议的约束。 
一、【协议的范围】
        本协议是URP用户与7GUGU之间关于用户架设或使用URP及其衍生品所订立的协议。“7GUGU” 主要指http://www.7gugu.com/网站及其运营和管理人员。“用户”是指架设或使用URP及其衍生品的架设者、使用人，以下也成为“您”。
二、【许可使用】
        2.1 通过任何途径下载的URP程序，在不违反本协议的前提下可自行架设、使用。
        2.2 其它参考URP及其衍生品的源代码的程序，须征得至少两位URP开发者同意后，在标示原有版权信息的情况下发行、架设、使用。
三、【禁止使用】
        3.1 不得以任何理由、任何手段（包括但不限于删减、遮挡、修改字号、添加nofollow属性等）修改URP及其衍生品原有的版权信息、版权链接的指向及友情链接，并保证原有版权能够在显眼处展示。
        3.2 禁止以任何形式向他人兜售URP的复制品或延伸产品。
        3.3 禁止使用URP以任何强制收费形式盈利，捐赠、挂广告等非强制收费形式除外。
四、【许可终止】
        4.1 无论何时，如果您主动放弃或被收回了许可，您必须立即销毁URP的所有复制品、衍生品，并关闭任何由URP搭建的服务。
        4.2 如您违反了本协议的任何一项条款和条件，则视为一切许可被收回。
        4.3 爱用不用，不用就滚，一旦您滚蛋，则视为一切许可被收回。
五、【权利保留】
        未明确声明的一切其它权利均为7gugu所保留，对本协议的一切解释权归7gugu所有。
六、【责任限度】
        在适用法律所允许的最大范围内，7gugu在任何情况下绝不就因使用或不能使用URP或就未提供支持服务所发生的任何特殊、意外、非直接或间接的损害负赔偿责任，即使事先被告知该损害发生的可能性。
七、【协议的生效与变更】
        7.1 7gugu有权在必要时修改本协议条款。您可以在相关页面查阅最新版本的协议条款。
        7.2 本协议条款变更后，如果您继续架设或使用URP及其衍生品，即视为您已接受修改后的协议。如果您不接受修改后的协议，那么视为您放弃一切许可（参见4.1）。
八、【适用和管辖法律】
        《中华人民共和国著作权法》、《中华人民共和国计算机软件保护条例》、《中华人民共和国商标法》、《中华人民共和国专利法》等中华人民共和国法律。
        【结语】
        本协议和上述有限保证及责任限制受中华人民共和国法律管辖。
        此外，在安装过程中，我们将会收集一些信息以便于统计安装量及改进体验。我们绝不会收集您的隐私信息，也不会向任何人泄露这些信息。
        至此，您肯定已经详细阅读并已理解本协议，并同意严格遵守全部条款和条件。
7gugu
</pre>
 <button class='am-btn am-btn-success'type='button' onclick="if(confirm('“我尊重原作者为URP付出的心血，在使用该系统的同时将保护原作者的版权。\r\n保证原作者的名称、链接等版权信息不被删改、淡化或遮挡，如果我没有做到，自愿承担由此引发的所有不良后果”\r\n\r\n同意请确定，不同意请取消')){location = 'install.php?step2';} else {alert('请立即删除所有与本程序相关的文件及其延伸产品');window.opener=null;window.open('','_self');window.close();}">我接受</button>
			 <button class='am-btn am-btn-danger'type='button' onclick="alert('请立即删除所有与本程序相关的文件及其延伸产品');window.opener=null;window.open('','_self');window.close();">我拒绝</button>
			 <br><br>
			 <?php }?>		
        </div>
      </div>
    </div>
    <footer class="admin-content-footer">
      <hr>
      <p class="am-padding-left">© 2016 Power By 7gugu.</p>
    </footer>
  </div>
  <!-- content end -->
</div>
<a href="#" class="am-icon-btn am-icon-th-list am-show-sm-only admin-menu" data-am-offcanvas="{target: '#admin-offcanvas'}"></a>
<script src="../assets/js/jquery.min.js"></script>
<script src="../assets/js/amazeui.min.js"></script>
<script src="../assets/js/app.js"></script>
</body>
</html>
