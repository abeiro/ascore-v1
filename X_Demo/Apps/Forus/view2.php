<?php

require_once("Forus.php");

$ID=(isset($ID))?$ID:1;
$max=4;
$p=newObject("post",$ID);

$origen=$p->traeOrigen();
$url=$p->traeUrl();
echo $SESSION['fecha'];
if ($origen!=$url){
$p->visitas++;
$p->save();
}
$f=newObject("foro",$foro_id);
$padre=$ID;
$s=newObject("post",$padre);
$defpost="Foro: <b><a href=\"index.php\">Foros</a> --> <a href=\"index2.php?foro_id=$foro_id\">{$f->title}</a> --> {$s->title}</b>";
$p->bread=$defpost;
plantHTML($p,"view");
$u=newObject("post");
setLimitRows($max);
$u->searchResults=$u->select("p_id=$ID",$offset,$sort);
$filas=_affected_rows();
if($filas==0)
$offset=0;
setNavVars(array("ID","foro_id"));	
$u->p_id=$ID;
$u->foro_id=$foro_id;
$external_data=array(
		"nuevo3"=>'code#if ($object->fecha >= '.$_SESSION['fecha'].')
		{
		$_SESSION["nuevo"]="verdadero";
		return "N U E V O";}');
listList($u,/*array()*/$external_data,"list_children");


	
?>