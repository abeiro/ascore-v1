<?php

require_once("Articulus.php");

$cat_id=(isset($cat_id))?$cat_id:1;
setNavVars(array("ID","cat_id"));
if($cat_id >1)
{
$u=newObject("categoria",$cat_id);
$u->MdP(&$u->MDP,$SYS["ROOT"]."Backend/Articulus/index2.php");

$u->searchResults=$u->select("cat_id=$cat_id",$offset,$sort);
$filas=$u->nRes;
if($filas==0)
$u->nohay="<tr><td align=\"center\"><b>No hay subcategorias</b></td></tr>";
$u->cat_id=$cat_id;
plantHTML(array("text"=>"Categoria:&nbsp;{$u->nombre}"),"titulo");
$u->isAdmin=BILO_isAdmin();
if(BILO_isAdmin())
  $u->crea="<div align=\"center\"><a href=\"nueva_categoria.php?cat_id={$u->cat_id}\" class=\"minibuttonik\">Crear</a></div>";
listList($u,array(),"list_cat");
}
$v=newObject("documento");



if($cat_id >1)
{  
  $c=newObject("categoria",$cat_id);
  
  $v->searchResults=$v->select("cat_id=$cat_id",$offset,$sort);
}else
  $v->searchResults=$v->selectAll($offset,$sort);
$filas=$v->nRes;
if(BILO_isAdmin())
  $colspan=5;
else
  $colspan=4;
if($filas==0)
  $v->nohay="<tr><td align=\"center\" colspan=\"$colspan\"><b>No hay documentos disponibles en este instante</b></td></tr>";

  $external_data=array(
    "categoria"=>"xref#categoria|cat_id|nombre");
$v->isAdmin=BILO_isAdmin();
listList($v,$external_data,"list_doc");



  
?>