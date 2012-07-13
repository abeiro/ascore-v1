<?php

require_once("Forus.php");
HTML("public-menu_foro");

setNavVars(array("ID","foro_id"));
$sort=(isset($sort))?$sort:"fecha DESC";
$u=newObject("foro");
$u->searchResults=$u->selectall($offset,$sort);
$total=$u->sumaMsg();
$external_data=array("grafica"=>'code#return $object->msgBar('.$total.');');
listList($u,$external_data,"public-list_foro");

	
?>