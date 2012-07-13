<?php

require_once("Forus.php");


$ID=(isset($ID))?$ID:1;

$p=newObject("foro",$ID);

$p->p_id=(isset($p_id))?$p_id:0;
if ($ID<2)
$p->visitas=0;

formAction("save_foro.php","footer","editForm");

$p->boton0=gfxBotonAction("Guardar","getElementById('editForm').submit()",True);

plantHTML($p,"foro");
formClose();

?>