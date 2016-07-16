  function savec(sid){
			   var xmlhttp=null;
			   var savec=editorc.getValue();
    if(window.XMLHttpRequest){
        //针对FF,Mozilar,Opera,Safari,IE7,IE8
        xmlhttp=new XMLHttpRequest();
        //修正某些浏览器bug
        if(xmlhttp.overrideMimeType){
            xmlhttp.overrideMimeType("text/xml");
        }
    }else if(window.ActiveXObject){
        //针对IE6以下的浏览器
        var activexName=["MSXML2.XMLHTTP","Microsoft.XMLHTTP",""];
        for( var i=0;i<activexName.length;i++){
            try{
                //取出一个控件名称创建,如果创建成功则停止,反之抛出异常
                xmlhttp=new ActiveXObject(activexName[i]);
                break;                
            }catch(e){    }
        }
    }  
    //注册回调函数。注意注册回调函数是不能加括号,加了会把函数的值返回给onreadystatechange
    xmlhttp.onreadystatechange=callback;
    //设置连接信息
    //第一个参数表示http请求方式,支持所有http的请求方式,主要使用get和post
    //第二个参数表示请求的url地址,get方式请求的参数也在urlKh 
    //第三介参数表示采用异步还是同步方式交互,true表示异步
 //   xmlhttp.open("GET","servlet/CheckUserName?userName=" + userName,true);
    //发送数据表示和服务器端交互
    //同步方式下,send这名话会在服务器端数据回来后才执行完
  //  xmlhttp.send(null);
    
    //异步方式下,send这句话立即完成执行
    //POST方式请求的代码
    xmlhttp.open("POST","manage.php?ser="+sid+"&&save&&cdat",true);
    //POST方式需要自己设置http的请求头
    xmlhttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    //POST方式发送数据
    xmlhttp.send("text="+savec);
	//回调函数
function callback(){
    //判断对象的状态是交互完成
    if(xmlhttp.readyState==4){
        //判断http的交互是否成功
        if(xmlhttp.status==200){
           console.log("修改成功");
		   	   $(function() {
    var $modal = $('#your-modal');
        $modal.modal();
    
  });
        }
    }
		   }
}
  function saver(){
			   var xmlhttp=null;
			   var savec=editorr.getValue();
    if(window.XMLHttpRequest){
        xmlhttp=new XMLHttpRequest();
        if(xmlhttp.overrideMimeType){
            xmlhttp.overrideMimeType("text/xml");
        }
    }else if(window.ActiveXObject){ 
        var activexName=["MSXML2.XMLHTTP","Microsoft.XMLHTTP",""];
        for( var i=0;i<activexName.length;i++){
            try{
                xmlhttp=new ActiveXObject(activexName[i]);
                break;                
            }catch(e){    }
        }
    }  
    xmlhttp.onreadystatechange=callback;
    xmlhttp.open("POST","manage.php?ser="+sid+"&&save&&rxml",true);
    xmlhttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    xmlhttp.send("text="+savec);
	//回调函数
function callback(){
    //判断对象的状态是交互完成
    if(xmlhttp.readyState==4){
        //判断http的交互是否成功
        if(xmlhttp.status==200){
           console.log("修改成功");
		   
		   $(function() {
    var $modal = $('#your-modal');
        $modal.modal();
    
  });
		   
        }
    }
		   }
}