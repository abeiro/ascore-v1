<?php

require_once("Forus.php");
HTML("action_header");
echo "Guardado";
if (isset($_POST["ID"])) {
	$form=newObject("foro",$ID);
	$form->setAll($_POST);
	$form->fecha=time();
	$form->save();
	
}


frameGo("fbody",'index.php');



?>