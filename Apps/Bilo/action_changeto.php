<?php

require_once("Bilo.php");

$user=newObject("user",$_GET["ID"]);
$_SESSION["__auth"]["backto"]=BILO_uid();
$_SESSION["__auth"]["username"]=$user->username;
$_SESSION["__auth"]["uid"]=$user->ID;



session_commit();

frameGo("window",$GLOBALS["SYS"]["ROOT"]);

?>