<?php 
  if(!file_exists(dirname(dirname(__FILE__))."/assets/install.lock"))
{
	?>
<link rel="stylesheet" href="./assets/css/amazeui.min.css"/>
<br>
<div class="am-g">
  <div class="am-u-sm-6 am-u-lg-centered">
  <div class=" am-alert am-alert-danger" data-am-alert>
  <h3>检测到无 install.lock 文件</h3>
  <p>Start.exe在安装完成后,需要一直开启,以此启动服务器</p>
  <ul>
    <li>如果您尚未安装本程序，请<a href="install/install.php"><code>前往安装</code></a></li>
    <li>如果您已经安装本程序，请手动放置一个空的 install.lock 文件到 /assets 文件夹下,<h2>为了您站点安全,在您完成它之前我们不会工作。</h2></li>
  </ul>
</div>
  </div>
</div>
<?php   
exit(); 
}
require 'config/config.php';
require 'dbcore.php';
require 'ucore.php';
require 'mcore.php';
if(DEBUG){
error_reporting(E_ALL);
}
		/*
		Ucon 2.0  core start 
		请勿改变启动流程!!!
		Power by 7gugu
		*/
?>
