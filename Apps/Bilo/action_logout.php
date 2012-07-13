<?php

require_once("Bilo.php");
HTML("action_header");
if (BILO_logout())
	frameGo("fbody","list.php");



?>