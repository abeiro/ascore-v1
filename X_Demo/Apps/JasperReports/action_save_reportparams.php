<?php
require_once("JasperReports.php");


//require_once("modificacion.php");
plantHTML(array(),'action_header');
$ID=(isset($ID))?$ID:1;
if($ID>1)
	$mod=newObject("reportparams",$ID);
else 
	$mod=newObject("reportparams");
	
$mod->setAll($_POST);


$pos=strpos($mod->body,"<br />");
echo $pos;
$mod->resumen=substr($mod->body,0,$pos);

if($mod->save()) {
	echo "Informe '$titulo' guardado correctamente";
	frameGo("fbody",'listadoreportparams.php');
	}
else
	echo "Error:  {$mod->error}";
		
HTML('action_footer');
?>