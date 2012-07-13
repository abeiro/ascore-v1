<?php

require_once("Bilo.php");



if (isset($_POST["ID"])) {
	if ($ID<2) {
		$form=newObject("data_object",$ID);
		$form->setAll($_POST);
		if ($_SESSION["__auth"]["userId"]!="administrador")
			$form->uid=$_SESSION["__auth"]["userId"];

		$form->save();
	
	}
	else {

		$form=newObject("data_object",$ID);
		$form->setAll($_POST);
		if ($_SESSION["__auth"]["userId"]!="administrador")
			$form->uid=$_SESSION["__auth"]["userId"];
		
		$form->save();
		
	
	}
}


require 'list.php';


?>