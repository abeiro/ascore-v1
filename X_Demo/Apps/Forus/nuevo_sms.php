<?php

require_once("Forus.php");
setNavVars(array("foro_id"));
$foro_id=(isset($foro_id))?$foro_id:1;
$sort=(isset($sort))?$sort:"fecha DESC";
$n=newObject("post");
$u=newObject("post");
$fech=$_SESSION['fecha'];
$u->searchResults=$u->select("fecha>=$fech and p_id=0",$offset,$sort);
if($u->nRes==0)
{
echo "<tr><td colspan=\"6\" ALIGN=\"CENTER\">EN ESTE MOMENTO NO EXISTE NINGÚN MENSAJE NUEVO</TD></TR></TABLE>";
}else{
$u->bread=$deffor;
$u->foro_id=$foro_id;//damos el valor primero al objeto sino se lo pasa vacio.
listList($u,array(),"list");
}
	
?>