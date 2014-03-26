<?php

/* Bloques */
/********** Construccion de la portada */
header("Location: {$GLOBALS["SYS"]["defaultmodule"]}");
die();
$SYS["MSG"]=" . asCore Development. ";
require_once("Bilo.php");
BILO_login();

if (!BILO_isLogged()) {
	plantHTML(
	array (
		"ROOT"=>$SYS["ROOT"],
		"MSG"=>($SYS["MSG"])?$SYS["MSG"]:"Entrada al Sistema",
		"SMSG"=>$SYS["MESSAGES"],
		"REFERER"=>$_SERVER["HTTP_REFERER"]
		),"entrada");
}
else  {
	
	
}
		


?>
