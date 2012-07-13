<?php

require_once("Bilo.php");
HTML("action_header");

$form=newObject("user",$ID);
if ($form->delete()) {

	echo _("Borrado correctamente");
	frameGo("fbody","list.php");
}
else
	echo _("No borrado");



?>