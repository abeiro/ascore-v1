<?php
require_once("Noticias.php");

$myclase=newObject("notice");
//$myclase->titulo="Otra y Otra";
//$myclase->save();
setLimitRows(10);


$myclase->searchResults=$myclase->selectAll($offset,$sort);

$referencias_externas=array(
	"id_cat"=>'code#if ($object->id_cat==10) return time();'
);

listList($myclase,$referencias_externas,"listado_ejemplo");


debug("#########################\nHola debug","cyan");

?>