<?php
require_once("Noticias.php");
HTML('action_header');
$u=newObject("cat_not");
$u->nombre_cat=$_POST["nombre_cat"];
$u->ID=$_POST["ID"];

if($u->save()) {
	echo "Guardado correctamente";
	frameGo("fbody",'list_cat.php');
	}
else
	echo $u->error;
HTML('action_footer');
?>