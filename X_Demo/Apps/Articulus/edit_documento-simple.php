<?php

require_once("Articulus.php");
require_once("Lib/lib_tree.php");
if(BILO_isLogged())
{
	$ID=(isset($ID))?$ID:1;
	$p=newObject("documento",$ID);
	
	$p->cat_id=(isset($cat_id))?$cat_id:1;
	if($p->cat_id>1)
	{
		$cat=newObject("categoria",$cat_id);
		$p->cat_pr=$cat->cat_or;
	}
	formAction("action_save_documento.php","footer","editForm");
	
	$wRes=new avSelectTree();
	$p->categoria=$wRes->avSelectPrintTree("cat_id","Articulus","categoria","nombre",$p->cat_id);
	
	
	$p->boton0=gfxBotonAction("Guardar","getElementById('editForm').submit()",True);
	$p->boton1=gfxBotonAction("Volver","history.go(-1)",True);
	$c=newObject("categoria");
	if ($ID<2)
		$p->autor=BILO_username();
	plantHTML($p,"documento-simple",array("cat_id"=>$c->ListCat()));
	formClose();
}else
{
	echo "No esta logueado";
}
?>