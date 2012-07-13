<?php
require_once("Biblio.php");
$c=newObject("cat_lib");
$c->isAdmin=BILO_isAdmin();

$c->searchResults=$c->selectAll($offset,$sort);
if($c->nRes==0)
	$c->nohay="NO HAY CATEGORIAS DISPONIBLES";
listList($c,array(),'list_catlib');
?>