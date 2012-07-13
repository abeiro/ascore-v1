<?php
require_once("Articulus.php");
HTML("action_header");
echo "Guardado";
if (isset($_POST["ID"])>1) {
	$form=newObject("categoria",$ID);
	$form->setAll($_POST);
	$form->fecha_mod=time();
	$form->save();
	
}else{
        $form=newObject("categoria",$ID);
	$form->setAll($_POST);
	$form->fecha_mod=time();
	$form->fecha_cre=time();
	if($form->cat_id >1)
	{
		$cat=newObject("categoria",$form->cat_id);
		$form->cat_pr=$cat->cat_pr;
	}else
	{
	$cat_pr=$form->save();
	$form=newObject("categoria",$cat_pr);
	$form->cat_pr=$cat_pr;
	}
	$form->save();
}

if($form->cat_id >2)
frameGo("body",'index2.php?cat_id='.$form->cat_id);
else
frameGo("body",'index.php');
?>