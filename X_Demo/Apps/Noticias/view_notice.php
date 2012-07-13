<?php
require_once("Noticias.php");
$ID=(isset($ID))?$ID:1;
$n=newObject("notice");
$n->isAdmin=BILO_isAdmin();
if($ID>1)
{
	$n=newObject("notice",$ID);
	$f=newObject("foto",$n->adjunto);
	$n->id_foto=$f->id_foto;
	$n->id_thumb=$f->id_thumb;
	$n->volver=gfxBotonAction("Volver","history.go(-1)",True);
	
	plantHTML($n,'view_notice');
	$n->visita++;
        $n->fech_ult_consulta=date(time());
	$n->save();

}else
	echo "<div align=\"center\"><B>ERROR EN LA P�GINA</B></DIV>";
?>