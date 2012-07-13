<?php

/* Logout point */

require_once("coreg2.php");
require_once("Bilo/API_exports.php");

if (BILO_logout()) 
		PlantHTML(array("location"=>$SYS["ROOT"]),"redirect");

?>