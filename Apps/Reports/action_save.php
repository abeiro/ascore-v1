<?php

require_once("Reports.php");
HTML("action_header");

if (isset($_POST["ID"])) {
	if ($ID<2) {
		$u=newObject("report",$ID);
		$u->activo='No';
		$u->setAll($_POST);
		if ($u->save()) {
			echo _("Grabado correctamente");
			//frameGo("fbody","index.php");
		}
		else {
			echo _("Error grabando: ").$u->ERROR;
			
		}
			
		
	}
	else {

		$u=newObject("report");
		$b=$u->_clone($u->properties);
		$u->activo='No';
		$u->setAll($_POST);
		if ($u->save()) {
			echo _("Grabado correctamente");
			//frameGo("fbody","index.php");
		}
		else
			echo _("Error grabando: ").$u->ERROR;
		
	}
}


HTML("action_footer");

?>