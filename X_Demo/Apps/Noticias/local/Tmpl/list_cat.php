<?php
require_once("Noticias.php");
$c=newObject("cat_not");
$c->isAdmin=BILO_isAdmin();

$c->searchResults=$c->selectAll($offset,$sort);
if($c->nRes==0)
	$c->nohay="NO HAY CATEGORIAS DISPONIBLES";
listList($c,array(),'list_cat');
?>