<?php

require_once("coreg2.php");
require_once("Bilo/API_exports.php");


if ($_POST["proceder"]) 
	if (BILO_login()) {
		$REFERER=(empty($_POST["REFERER"]))?$SYS["ROOT"]."Backend/":$_POST["REFERER"];
		PlantHTML(array("location"=>$REFERER),"redirect");
		die();
		
	}
else {
	//PlantHTML(array("location"=>$_POST["REFERER"]),"redirect");
	$SYS["MESSAGES"]=_("Error, user/password wrong (".$AUTH["error"].")");
	include("Apps/Bilo/login.php");
	
}
?>