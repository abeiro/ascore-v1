<?php
require_once("Noticias.php");



$ID=(isset($ID))?$ID:1;
if($ID>1)
	$n=newObject("notice",$ID);
else
	$n=newObject("notice");
	
formAction("action_save_notice_simple.php?ID=$ID","footer","editForm");


$n->boton0=gfxBotonAction("Guardar","getElementById('editForm').submit()",True);

$c=newObject("cat_not");
$d=array(
	"id_cat"=>$c->listAll("nombre_cat")
	
);

plantHTML($n,'add_simple',$d);
formClose();

?>