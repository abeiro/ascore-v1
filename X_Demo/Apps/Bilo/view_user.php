<?php


require_once("Bilo.php");
plantHTML(array(),"f_menu");

$ID=(isset($ID))?$ID:1;

$u=newObject("user",$ID);
$u->barcode=(sprintf("%4d",$ID+1000000));
	
$external_data=array(
	"grupos_nombre"=>"fref#user|ID|listGroupsNames"
);
	
plantView($u,$external_data,"view_user");
formClose();

require_once("Lib/lib_barcode.php");



HTML("footer");
?>
