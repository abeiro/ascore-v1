<?php


require_once("JasperReports.php");


$ID=(isset($ID))?$ID:1;

$u=newObject("report",$ID);

$u->boton1=gfxBotonAction("<<","document.location.href=document.location.href+'&ID=".($u->prevID())."'",True);
	$u->boton2=gfxBotonAction(">>","document.location.href=document.location.href+'&ID=".($u->nextID())."'",True);
	

	$wRes=new avSelect();
	$u->inputQuery=$wRes->avSelectPrint("query_id","Bilo","queryb","nombre",$u->query_id);
	
	formAction("action_save.php","footer","editForm");
	$u->boton0=gfxBotonAction("Guardar",
	"getElementById('editForm').submit()",True);
	$external_data=array("tipo"=>array("HardCoded"=>"HardCoded","SoftCoded"=>"SoftCoded"));
	plantHTML($u,"edit",$external_data);
	formClose();
//dataDump($_SESSION);

?>
