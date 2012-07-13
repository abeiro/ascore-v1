<?php


require_once("Bilo.php");


$ID=(isset($ID))?$ID:1;

if (($ID==2)&&(!BILO_isAdmin()))
	die('<h4 align="center">Privilegios insuficientes</h4>');
$u=newObject("user",$ID);
$u->grupos=$u->listGroupsIndex();


$g=newObject("group");

$u->boton1=gfxBotonAction("<<","document.location.href=document.location.href+'&ID=".($u->prevID())."'",True);
$u->boton2=gfxBotonAction(">>","document.location.href=document.location.href+'&ID=".($u->nextID())."'",True);



if (BILO_isAdmin()) {
		
	formAction("action_user_save.php?codename=$codename","footer","editForm");
	$u->boton0=gfxBotonAction("Guardar",
	"getElementById('editForm').submit()",True);
	$external_data=array("grupos"=>$g->listGroupIndex());
	plantHTML($u,"edit_user",$external_data);
	formClose();

}
else if (BILO_isOperator()) {
	formAction("action_user_save.php?codename=$codename","footer","editForm");
	$g->getGroupByName("Operadores");
	$gid=$g->ID;
	$u->boton0=gfxBotonAction("Guardar",
	"getElementById('editForm').submit()",True);
	$external_data=array("grupos"=>array($g->ID=>$g->groupname));
	
	plantHTML($u,"edit_user",$external_data);
	formClose();
}
else 
	echo '<h4 align="center">Privilegios insuficientes</h4>';

?>
