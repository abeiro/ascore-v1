<?php

require_once("Bilo.php");
HTML("action_header");

$userid=$usercode-1000;
$u=newObject("user",1);
$search=$u->select("ID=$userid");
if ($u->nRes>0) {
	$u=newObject("user",$userid);
	echo $u->nombre." ".$u->apellidos;
	frameReload("fbody");
} else
	echo "Error $userid";
	
HTML("action_footer");
?>

