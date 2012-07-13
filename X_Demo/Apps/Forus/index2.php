<?php

require_once("Forus.php");
setNavVars(array("foro_id"));
$foro_id=(isset($foro_id))?$foro_id:1;
$sort=(isset($sort))?$sort:"fecha DESC";
$n=newObject("post");
$u=newObject("post");
$f=newObject("foro",$foro_id);
$deffor="Foro: <b><a href=\"index.php\">Foros</a> --> {$f->title}</b>";

$url=$PHP_SELF;
if ($cont=="true")
  $f->visitas++;
  $f->save();
$u->searchResults=$u->select("p_id<2 and foro_id=$foro_id",$offset,$sort);

$u->bread=$deffor;
$padre=$f->ID;
$total=$f->sumaMsg2();
$id=$u->ID;
$external_data=array(
	"grafica"=>'code#return $object->msgBar('.$total.');',
"nuevo2"=>'code#if ($object->fecha >= '.($_SESSION['fecha']+1).')
		return "Nuevo";');
echo $parcial;
//lo anterior es la forma de pasar el resultado de un if u otro bucle.
$u->foro_id=$foro_id;//damos el valor primero al objeto sino se lo pasa vacio.
listList($u,$external_data,"list");

?>