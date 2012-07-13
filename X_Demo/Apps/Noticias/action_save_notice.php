<?php
require_once("Noticias.php");
plantHTML(array(),'action_header');
$ID=(isset($ID))?$ID:1;
if($ID>1)
	$n=newObject("notice",$ID);
else 
	$n=newObject("notice");
	

$f=newObject("foto");
$f->CaptureFile='adjunto';
//$f->familia_fileh_ID=_NOTICIAS;)
if(!empty($_FILES[$f->CaptureFile]["name"]))
	$n->adjunto=$f->save();
else
	$n->adjunto=$_POST["adjunto"];
	
	//$n->adjunto=$f->save();

$n->setAll($_POST);




$pos=strpos($n->body,"<br />");
echo $pos;
$n->resumen=substr($n->body,0,$pos);

if($n->save()) {
	echo "Noticia '$titulo' guardado correctamente";
	frameGo("fbody",'index.php');
	}
else
	echo "Error:  {$n->error}";
	
	
HTML('action_footer');

?>