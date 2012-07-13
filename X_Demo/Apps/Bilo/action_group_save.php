<?php

require_once("Bilo.php");
HTML("action_header");



$u=newObject("group");
$u->setAll($_POST);


if ($u->save()) {
	echo _("Guardado");
	frameGo("fbody","list_group.php");	
}
else {
	echo _("Error");
	
}


?>