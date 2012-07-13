<?php
require_once("GlobalSche/GlobalSche.php");
plantHTML(array(),'action_header');
$ID=(isset($ID))?$ID:1;
if($ID>1)
  $n=newObject("gstep",$ID);
else 
  $n=newObject("gstep");
  

$n->setAll($_POST);
if($n->save()) {
  echo "Paso ".$_POST["titulo"]." guardado correctamente";
  
  frameGo("fbody",'step_list.php');
  }
else
  echo "Error:  {$n->error}";
  
  
HTML('action_footer');

?>