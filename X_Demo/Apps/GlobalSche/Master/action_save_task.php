<?php
require_once("GlobalSche/GlobalSche.php");
plantHTML(array(),'action_header');
$ID=(isset($ID))?$ID:1;
if($ID>1)
  $n=newObject("gtask",$ID);
else 
  $n=newObject("gtask");
  

$n->setAll($_POST);
if($n->save()) {
  echo "Tarea ".$_POST["titulo"]." guardada correctamente";
  
  frameGo("fbody",'task_list.php');
  }
else
  echo "Error:  {$n->error}";
  
  
HTML('action_footer');

?>