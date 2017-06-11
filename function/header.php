<?php 
echo "
<header class='am-topbar am-topbar-inverse admin-header'>
  <div class='am-topbar-brand'>
    <strong>".HNAME."</strong> <small>后台管理</small>
  </div>
  <div class='am-collapse am-topbar-collapse' id='topbar-collapse'>
  
  <ul class='am-nav am-nav-pills am-topbar-nav am-topbar-right admin-header-list'>
      <li class='am-dropdown' data-am-dropdown=''>
        <a class='am-dropdown-toggle' data-am-dropdown-toggle='' href='javascript:;'>
          <span class='am-icon-user'></span>  {$_SESSION['username']} 
        </a>
    </li></ul>
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
