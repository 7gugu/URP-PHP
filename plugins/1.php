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