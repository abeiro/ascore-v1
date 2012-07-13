<?php

require_once("Forus.php");
HTML("public-f_menu");

$ID=(isset($ID))?$ID:1;
$foro_id=(isset($foro_id))?$foro_id:1;
$p=newObject("post",$ID);
$p->p_id=(isset($p_id))?$p_id:0;
$p->foro_id=$foro_id;
formAction("action_save.php?ID=$ID&foro_id={$p->foro_id}","footer","editForm");

$p->boton0=gfxBotonAction("Guardar","getElementById('editForm').submit()",True);

plantHTML($p,"public-post");

formClose();
	
?>

