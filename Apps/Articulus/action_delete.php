<?php

require_once("Articulus.php");
plantHTML(array(),'action_header');

$ID=(isset($ID))?$ID:1;
$cat_id=(isset($cat_id))?$cat_id:1;
$type=(isset($type))?$type:"";
setNavVars(array("ID","cat_id"));


$filas=$u->nRes;
echo $filas;


	if($type=="cat")
	{
		$u=newObject("categoria",$ID);
		$u->searchResults=$u->select("cat_id=$ID");
		if($filas>1)
		{
			echo "No se puede borrar, contiene $filas objetos";
		}else
		{
		$g=newObject("categoria",$ID);
		$g->delete();
		}
	}
	if($type=="doc")
	{
		echo "Borrando....";
		$d=newObject("documento",$ID);
		if($d->delete())
			echo "Borrado ok";
		else
			echo "Error al borrar";
	}
	

echo $cat_id;
if($cat_id<2)
frameGo("fbody",'index2.php');
else
frameGo("fbody",'index2.php?cat_id='.$cat_id);

?>