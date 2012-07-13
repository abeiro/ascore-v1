<?php

require_once("Reports.php");
HTML("action_header");

$u=newObject("report",$ID);
if ($u->delete()) {
	echo _("Borrado correctamente");
	frameReload("fbody");
}
else {
	echo _("Error Borrando: ").$u->ERROR;
	
}


HTML("action_footer");

?>