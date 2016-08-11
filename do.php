<?php 
require 'function/corestart.php';
ignore_user_abort(true);
set_time_limit(0);
if($restart==true){
	//ÖØÆôÄ£¿é
while ( $row = mysqli_fetch_array ( query("select * from server ") ) ) {
        rcon("shutdown",1,$row['rport'],$row['rpw']);
	    rcon($row['sid'],0,1935,'');
}
}

?>