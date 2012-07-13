<?php

require_once("Forus.php");
plantHTML($_GET,"public-f_menu");
$ID=(isset($ID))?$ID:1;
$max=4;
$p=newObject("post",$ID);
$origen=$p->traeOrigen();
$url=$p->traeUrl();

if ($origen!=$url){
$p->visitas++;
$p->save();
}
$f=newObject("foro",$foro_id);
$padre=$ID;
$s=newObject("post",$padre);
$defpost="Foro: <b><a href=\"index.php\">Foros</a> --> <a href=\"index2.php?foro_id=$foro_id\">{$f->title}</a> --> {$s->title}</b>";
echo $defpost;
plantHTML($p,"public-view");

$u=newObject("post");
setLimitRows($max);
$u->searchResults=$u->select("p_id=$ID",$offset,$sort);

$filas=_affected_rows();
if($filas==0)
$offset=0;


setNavVars(array("ID","foro_id"));

listList($u,array(),"list_children");
plantHTML($p,"public-view_menu");

	
?>