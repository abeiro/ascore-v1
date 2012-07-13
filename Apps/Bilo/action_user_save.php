<?php

require_once("Bilo.php");
HTML("action_header");

if (isset($_POST["ID"])) {
	if ($ID<2) {
		$u=newObject("user",$ID);
		$u->activo='No';
		$u->setAll($_POST);
		$u->password=md5($u->password);
		$u->grupos=$u->setGroupCode($_POST["grupos"]);
		$NID=$u->save();
		if ($NID) {
			echo _("Grabado correctamente {$u->username}");
			if ($ID<2) {
				frameGo("fbody","list.php?OID=$NID");
			}
		}
		else {
			echo _("Error grabando {$u->username}: ").$u->ERROR;
			
		}
			
		
	}
	else {

		$u=newObject("user",$ID);
		$b=$u->_clone($u->properties);
		
		$u->properties["activo"]='';
		$u->setAll($_POST);
	
		
		if (!empty($_POST["password"]))
			$u->password=md5($u->password);
		else
			$u->password=$b->password;		
			
		$u->grupos=$u->setGroupCode($_POST["grupos"]);
		if ($u->save()) {
			echo _("Grabado correctamente {$u->username}");
			//frameGo("fbody","list.php");
		}
		else
			echo _("Error grabando {$u->username}: ").$u->ERROR;
		
	}
}




?>