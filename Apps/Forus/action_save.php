<?php

require_once("Forus.php");
HTML("action_header");
echo "Guardado";
if (isset($_POST["ID"])) {
	$form=newObject("post",$ID);
	
	$form->setAll($_POST);
	
	$form->msg=htmlentities(strip_tags($form->msg));
	$form->fecha=time();
	
	if($form->p_id>1){
   
	   if ($ID<2)
	   {
	   	$padre=newObject("post",$form->p_id);
	   	$padre->respuestas++;
		$padre->autormsg=$form->autor;
		$padre->fecha=$form->fecha;
		$padre->save();
		
	   
	   }
	   
	   
	}
	if ($ID<2)
	   {	$mensajes=newObject("foro",$form->foro_id);
	   	$mensajes->msg++;
		$mensajes->fecha=$form->fecha;
		$mensajes->autormsg=$form->autor;
	   	$mensajes->save();
	   }
	$form->save();
	
	
	
}

if ($form->p_id > 1) {
        $ID=$form->p_id;
	frameGo("fbody",'view.php?ID='.$ID.'&foro_id='.$form->foro_id);
}
else
	frameGo("fbody",'index2.php?&foro_id='.$form->foro_id);;

?>