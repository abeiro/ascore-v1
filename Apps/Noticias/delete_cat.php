<?php
require_once("Noticias.php");
$c=newObject("cat_not");
$c->isAdmin=BILO_isAdmin();
plantHTML($c,"f_menu_noticias");
$ID=(isset($ID))?$ID:1;

if($ID>1)
{
	$u=newObject("cat_not",$ID);
	$u->delete();
}else
	echo "ERROR";
	
FrameGo("fbody","list_cat.php");


?>