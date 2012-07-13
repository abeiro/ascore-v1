<?php
require_once("Biblio.php");
HTML('action_header');
$u=newObject("cat_lib");
$u->nombre_cat=$_POST["nombre_cat"];
$u->ID=$_POST["ID"];

if($u->save()) {
	echo "Guardado correctamente";
	frameGo("fbody",'list_catlib.php');
	}
else
	echo $u->error;
HTML('action_footer');
?>