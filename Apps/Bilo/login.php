<?php

require_once("Bilo.php");

if (!BILO_isLogged()) {
	plantHTML(
	array (
		"ROOT"=>$SYS["ROOT"],
		"MSG"=>($SYS["MSG"])?$SYS["MSG"]:"Entrada al Sistema",
		"SMSG"=>$SYS["MESSAGES"],
		"REFERER"=>$_SERVER["HTTP_REFERER"]
		),"entrada");
}
else
	PlantHTML(array("location"=>$SYS["ROOT"]."Backend/"),"redirect");

?>