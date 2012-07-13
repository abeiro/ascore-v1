<?php

require_once("Bilo.php");
HTML("action_header");

if ($_POST["proceder"]) {
	if (BILO_login()) 
		frameGo("fbody","registro.php");
	echo $SYS["ERROR"];

	
}

