<?php

require_once("Forus.php");


$ID=(isset($ID))?$ID:1;
$s=newObject("post",$p_id);
$foro_id=(isset($foro_id))?$foro_id:1;
$p=newObject("post",$ID);
$leido=(isset($leido))?$leido:1;
$a=newObject("post",$leido);
$p->title=$s->title;

$p->user=BILO_username();

if($p->title != "")
{
$p->resp="readonly=\"true\"";
}

$p->p_id=(isset($p_id))?$p_id:0;
$p->foro_id=$foro_id;

formAction("action_save.php?ID=$ID&foro_id={$p->foro_id}&leido=","footer","editForm");

$p->boton0=gfxBotonAction("Guardar","getElementById('editForm').submit()",True);

plantHTML($p,"post");

formClose();

?>

