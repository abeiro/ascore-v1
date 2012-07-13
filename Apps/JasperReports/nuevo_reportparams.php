<?php
require_once("JasperReports.php");
$ID=(isset($ID))?$ID:1;
    $ane=newObject("reportparams",$ID);

	
	formAction("action_save_reportparams.php","footer","editForm");
	$ane->boton0=gfxBotonAction("Guardar",
		"getElementById('editForm').submit()",True);
		

$c=newObject("jasperreport");

$d=array(
	"jasperreport_id"=>$c->listAll("reportname"),
);

	plantHTML($ane,"add_reportparams",$d);
	formClose();
?>

