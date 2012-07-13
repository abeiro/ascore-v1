<?php
require_once("Bilo.php");


$p=newObject("user_pref");
$p->getPrefByUser(BILO_uid());
formAction("action_pref_save.php","footer","editForm");
$p->boton0=gfxBotonAction("Guardar","getElementById('editForm').submit()",True);
$external_data=array();
	
plantHTML($p,"user_pref",$external_data);
formClose();


?>
