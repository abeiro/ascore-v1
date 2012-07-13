<?php
require_once("Noticias.php");
$ID=(isset($ID))?$ID:1;
if ($ID>1)
{
	$n=newObject("notice",$ID);
	$n->delete();

}
FrameReload("fbody");
?>