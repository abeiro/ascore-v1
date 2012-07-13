<?php

require_once("Bilo.php");




if (BILO_isAdmin()) {
	$g=newObject("group",$ID);

	$g->boton1=gfxBotonAction("<<","document.location.href=document.location.href+'&ID=".($g->prevID())."'",True);
	$g->boton2=gfxBotonAction(">>","document.location.href=document.location.href+'&ID=".($g->nextID())."'",True);
	
	if (!isset($ID)) 
		$g=newObject("group",$g->nextIDFree());
	
		
		formAction("action_group_save.php","footer","editForm");
		$g->boton0=gfxBotonAction("Guardar",
		"getElementById('editForm').submit()",True);
			
		plantHTML($g,"edit_group",$external_data);
		formClose();
	//dataDump($_SESSION);
}
else {
	echo '<h4 align="center">Privilegios insuficientes</h4>';	
}

?>
