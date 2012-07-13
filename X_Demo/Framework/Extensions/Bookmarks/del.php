<?php

require_once("coreg2.php");
HTML("action_header");

$form=newObject("bookmark",$ID);

if ($form->user_id==BILO_uid())
	if ($form->delete()) {
	
		echo _("Borrado correctamente");
		
	}
	else
		echo _("No borrado");
else
	echo _("Sin permiso");


?>