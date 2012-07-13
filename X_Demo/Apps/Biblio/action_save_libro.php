<?php
require_once("Biblio.php");
HTML("action_header");

$libro=newObject("libro",$ID);
$libro->setAll($_POST);
if ($libro->save()) {
	echo "Guardado Correctamente";
	frameGo("fbody","main.php");
}
else
	echo "Error";
?>