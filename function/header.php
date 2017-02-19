<?php 
echo "
<header class='am-topbar am-topbar-inverse admin-header'>
  <div class='am-topbar-brand'>
    <strong>URP</strong> <small>后台管理</small>
  </div>
  <div class='am-collapse am-topbar-collapse' id='topbar-collapse'>
<li class=‘am-dropdown’ data-am-dropdown>
    <ul class='am-nav am-nav-pills am-topbar-nav am-topbar-right admin-header-list'>
          <span class='am-icon-user'></span>   {$_SESSION['username']} 
     </ul>
     </li>
  </div>
</header>
		 
";
/*
┏━━┓┏━━┓┏━━┓
┃┏━┛┃┏━┛┃┏━┛
┃┗━┓┃┗━┓┃┗━┓
┃┏┓┃┃┏┓┃┃┏┓┃
┃┗┛┃┃┗┛┃┃┗┛┃
┗━━┛┗━━┛┗━━┛

*/
?> 
