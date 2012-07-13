<?php

require_once("Forus.php");

setNavVars(array("foro_id"));
$foro_id=(isset($foro_id))?$foro_id:1;
$sort=(isset($sort))?$sort:"fecha DESC";
$u=newObject("post");
$f=newObject("foro",$foro_id);
$deffor="<br>Foro: <b><a href=\"index.php\">Foros</a> --> {$f->title}</b>";
echo $deffor;
$url=$PHP_SELF;
if ($cont=="true")
  $f->visitas++;
  $f->save();
$u->searchResults=$u->select("p_id<2 and foro_id=$foro_id",$offset,$sort);
$external_data=array("grafica"=>'code#return $object->msgBar('.$f->msg.');');
listList($u,$external_data,"Forus/list_post");
	
?>