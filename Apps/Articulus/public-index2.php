<?php

require_once("Articulus.php");
plantHTML($_GET,"menu_cat");

$cat_id=(isset($cat_id))?$cat_id:1;
setNavVars(array("ID","cat_id"));
$u=newObject("categoria",$cat_id);
$cat="Categoria: <b>{$u->nombre}</b><br>";
echo $cat;
$u->searchResults=$u->select("cat_id=$cat_id",$offset,$sort);
$filas=_affected_rows();
if($filas>0)
listList($u,array(),"list_cat");
else
echo "No hay subcategorias<br>";
$v=newObject("documento");
$v->searchResults=$v->select("cat_id=$cat_id",$offset,$sort);
$filas=_affected_rows();
if($filas>0)
listList($v,array(),"list_doc");
else
echo "No hay documentos<br>";

if($u->cat_id>1)
plantHTML($u,"view_cat");
else
plantHTML($u,"menu_todo");
	
?>