<?php

require_once("Articulus.php");
$cat_id=(isset($cat_id))?$cat_id:0;

$ID=(isset($ID))?$ID:1;
if($ID >1)
	$p=newObject("categoria",$ID);
else 
	$p=newObject("categoria");

if($cat_id>1)
{
	$c=newObject("categoria",$cat_id);
	$cat_pr=$c->cat_pr;	
	$p->sub="Subcategoría para:<b> {$c->nombre} </b>";
}

$p->cat_id=$cat_id;

$p->cat_pr=$cat_pr;
formAction("action_save_categoria.php","footer","editForm");

$p->boton0=gfxBotonAction("Guardar","getElementById('editForm').submit()",True);
plantHTML($p,"categoria");

formClose();

?>


