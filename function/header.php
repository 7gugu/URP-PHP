<?php 
echo "
<header class='am-topbar am-topbar-inverse admin-header'>
  <div class='am-topbar-brand'>
    <strong>URP</strong> <small>后台管理</small>
  </div>

  <button class='am-topbar-btn am-topbar-toggle am-btn am-btn-sm am-btn-success am-show-sm-only' data-am-collapse='{target: '#topbar-collapse'}'><span class='am-sr-only'>导航切换</span> <span class='am-icon-bars'></span></button>

  <div class='am-collapse am-topbar-collapse' id='topbar-collapse'>

    <ul class='am-nav am-nav-pills am-topbar-nav am-topbar-right admin-header-list'>
      <li class='am-dropdown' data-am-dropdown>
        <a class='am-dropdown-toggle' data-am-dropdown-toggle href='javascript:;'>
          <span class='am-icon-users'></span>   {$_SESSION['username']} 
        </a>
      </li>
     </ul>
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
