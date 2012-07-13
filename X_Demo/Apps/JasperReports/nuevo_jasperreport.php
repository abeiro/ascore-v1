<?php
require_once("JasperReports.php");
$ID=(isset($ID))?$ID:1;
    $ane=newObject("jasperreport",$ID);

	
	formAction("action_save_jasperreport.php","footer","editForm");
	$ane->boton0=gfxBotonAction("Guardar",
		"getElementById('editForm').submit()",True);
		




	plantHTML($ane,"add_jasperreport",array());
	formClose();
?>

