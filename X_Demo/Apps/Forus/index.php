<?php
if(!isset ($_SESSION['fecha'])){
$us=newObject("user",BILO_uid());//BILO-uid nos da el id del usuario actual.
$us->searchResults=$us->select("ID = 'BILO_uid'");
//$_SESSION['fecha']=$us->fecha_hoy;
$_SESSION['fecha']=$us->fecha_hoy;
}

require_once("Forus.php");
	setNavVars(array("ID","foro_id"));
	$sort=(isset($sort))?$sort:"fecha DESC";
	$us=newObject("user",BILO_uid());//BILO-uid nos da el id del usuario actual.
	$us->searchResults=$us->select("ID = 'BILO_uid'");
	//$_SESSION['fecha']=$us->fecha_hoy;
	//$_COOKIE['fecha']=$us->fecha_hoy;
	$us->fecha_hoy=date(time());
	$us->save();
	$u=newObject("foro");
	$u->searchResults=$u->selectAll($offset,$sort);
	
	if($u->nRes==0)
	{
	echo "<tr><td colspan=\"6\" ALIGN=\"CENTER\">NO EXISTE EN ESTE MOMENTO NINGÚN FORO DISPONIBLE</TD></TR></TABLE>";
	}else{
		$total=$u->sumaMsg();
		$external_data=array(
		"grafica"=>'code#return $object->msgBar('.$total.');',
		"nuevo"=>'code#if ($object->fecha >= '.($_SESSION['fecha']+1).') return "Nuevos Mensajes";'
		);
		listList($u,$external_data,"list_foro");
 	     }
?>