<?php
	$c0="
<form class='am-form' name='c0' id='c0' action='create.php?c1' method='post' onsubmit='return check(this)'>
 <script type='text/javascript'>
function check(form){
if(form.inser.value==''){
alert('激活码不能为空！');
form.inser.focus();
return false;
}
if(form.inserpassword.value==''){
alert('激活密码不能为空！');
form.inserpassword.focus();
return false;
}
return true;
}
    </script>
  <br>
  <fieldset>
    <legend>准备工作[Step-0]</legend>
    <div class='am-form-group'>
        <label for='doc-ipt-email-1'>服务器激活码</label>
        <input id='inser' name='inser' type='text' class='' placeholder='输入激活码'>
		<br>
		  <input id='inserpassword' name='inserpassword' type='text' class='' placeholder='输入激活密码'>
      </div>
	<hr>
<button type='submit' class='am-btn am-btn-danger' >创建服务器</button>
  </fieldset>
</form>
";
$c1a= "
 <script type='text/javascript'>
function check(form){
if(form.servername.value==''){
alert('服务器名不能为空！');
form.servername.focus();
return false;
}
return true;
}
    </script>
<form class='am-form' name='c0' id='c0' action='create.php?c2' method='post' onsubmit='return check(this)'>
    <fieldset>
      <legend>创建服务器[Step-1]</legend>
      <div class='am-form-group'>
        <label for='doc-ipt-email-1'>服务器名</label>
        <input id='servername' name='servername' type='text' class='' placeholder='输入服务器名'>
      </div>
	   <div class='am-form-group'>
        <label for='doc-ipt-email-1'>玩家上限</label>
        <input id='players' name='players' type='text' class='' placeholder='输入玩家上限'>
      </div>
    <div class='am-form-group'>
        <label for='doc-ipt-pwd-1'>游戏地图</label>
        <select name='map' id='doc-select-'>
	";
	$c1b="
        </select>
        <span class='am-form-caret'></span>
      </div> <div class='am-form-group'>
        <label for='doc-ipt-pwd-1'>游戏难度</label>
        <select name='dif' id='doc-select-1'>
          <option value='easy'>Easy</option>
          <option value='normal'>Normal</option>
   	<option value='difficult'>Difficult</option>
   	<option value='gold'>Gold</option>
        </select>
      </div>
	  <div class='am-form-group'>
        <label for='doc-ipt-pwd-1'>竞技模式</label>
        <select name='pv' id='doc-select-1'>
          <option value='pvp'>PVP</option>
          <option value='pve'>PVE</option>
        </select>
      </div>
	  
	    <div class='am-form-group'>
        <label for='doc-ipt-pwd-1'>游戏视角</label>
        <select name='view' id='doc-select-1'>
          <option value='both'>Both</option>
          <option value='first'>First</option>
		  <option value='third'>Third</option>
        </select>
      </div>	  
   <div class='am-form-group'>
        <label for='doc-ipt-pwd-1'>是否开启作弊</label>
        <select name='cheat' id='doc-select-1'>
          <option value='off'>关闭</option>
          <option value='on'>开启</option>
        </select>
      </div>
	    <div class='am-form-group'>
        <label for='doc-ipt-email-1'>服务器可用时间(天/DAY)</label>
      ";
	  $c1c="
      </div>
      <div class='am-radio'>
        <label>
          <input type='radio' name='doc-radio-1' value='option1' checked=''>
          我同意遵守
   	<a href='#' data-am-modal='{target: '#doc-modal-1', closeViaDimmer: 0, width: 400, height: 225}'>网站服务条款</a>   
     <div class='am-modal am-modal-no-btn' tabindex='-1' id='doc-modal-1'>
    <div class='am-modal-dialog'>
      <div class='am-modal-hd'>网站服务条款
        <a href='javascript: void(0)' class='am-close am-close-spin' data-am-modal-close=''>×</a>
      </div>
      <div class='am-modal-bd'>
   你买了啥,出了问题都不关我们事OvO
       </div>
    </div>
   </div>  
     </label>
      </div>
      <p><button type='submit' class='am-btn am-btn-default'>创建</button></p>
    </fieldset>
	</form>";
	$c2="
	  <br>
  <fieldset>
    <legend>创建完成[Step-3]</legend>
    <div class='am-form-group'>
      <label for='doc-ipt-email-1'>服务器生成完成</label>
 <div class='am-progress am-progress-striped am-progress-sm am-active '>
  <div class='am-progress-bar am-progress-bar-secondary'  style='width: 100%'></div>
</div>
<p>你现在有如下选择:</p>
<ul>
<li>
可到产品页面查看自己的服务器
</li>
<li>
继续氪金买配额
</li>
</ul>
    </div>
	<hr>
<button type='button' class='am-btn am-btn-danger' onclick=\"javascript:window.location.href='list.php'\">返回列表</button>
  </fieldset>
	";
	?>