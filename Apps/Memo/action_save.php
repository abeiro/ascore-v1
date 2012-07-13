<?php

require_once("Memo.php");
require_once("security.php");

HTML("action_header");	
if (isset($_POST["ID"])) {
	if ($ID<2) {
		$form=newObject("data_object",$ID);
		$form->setAll($_POST);
		if (!BILO_isAdmin())
			$form->uid=BILO_uid();

		if ($form->save()) {
			echo $form->nombre._(" guardado");
			frameGo("fbody","list.php?inode=".$form->inode);
		}
		else
			echo $form->nombre._(" no guardado");
	}
	else {

		$form=newObject("data_object",$ID);
		$form->setAll($_POST);
		if (!BILO_isAdmin())
			$form->uid=BILO_uid();
		
		if ($form->save()) {
			
			echo $form->nombre._(" guardado");
			frameGo("fbody","list.php?inode=".$form->inode);
		}
		else
			echo $form->nombre._(" no guardado");
	
	}
}

HTML("action_footer");



?>