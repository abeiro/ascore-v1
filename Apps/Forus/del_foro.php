<?php

require_once("Forus.php");
HTML("action_header");

$ID=(isset($ID))?$ID:1;
$foro_id=$ID;
$d=newObject("foro",$ID);
$e=newObject("post",$foro_id);
$e->deletes("foro_id=$foro_id");
$d->delete();
echo "Borrado ok";
frameReload("fbody");
	
?>