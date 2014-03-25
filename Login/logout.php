<?php

/* Logout point */

require_once("coreg2.php");
require_once("Bilo/API_exports.php");

BILO_logout();
PlantHTML(array("location"=>$SYS["ROOT"]),"redirect");

?>