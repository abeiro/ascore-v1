<?php
require_once("Biblio.php");

    $libro=newObject("libro",$ID);

	
	formAction("action_save_libro.php","footer","editForm");
	$libro->boton0=gfxBotonAction("Guardar",
		"getElementById('editForm').submit()",True);
		

$c=newObject("cat_lib");
dataDump($c->listAll("nombre_cat"));

$d=array(

	"categoria"=>$c->listAll("nombre_cat")
	
);
	plantHTML($libro,"fichalibro",$d);
	formClose();
?>