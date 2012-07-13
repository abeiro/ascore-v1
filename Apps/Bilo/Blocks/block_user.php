<?php

require_once("Bilo.php");

/* Bloques */

function BILO_block_user() {
	
	global $SYS;
	ob_start();
	if (BILO_isLogged()) {
		$u=newObject("user",BILO_uid());
		$u->username=BILO_username();
		$u->clase="login";
		plantHTML($u,"Public/block_ok_login");
	}
	else {
		$u=newObject("user");
		debug(BILO_username(),"blue");
		$u->clase="logoff";
		plantHTML($u,"Public/block_no_login");
		
	}
	$data=ob_get_contents();
	ob_end_clean();	
	return $data;
}
?>