<?php
require_once("GlobalSche/GlobalSche.php");
plantHTML(array(),'action_header');
$ID=(isset($ID))?$ID:1;
if($ID>1)
  $n=newObject("gtasklog",$ID);
else 
  $n=newObject("gtasklog");
  

$n->setAll($_POST);
if($n->save()) {
  echo "Registro ".$_POST["titulo"]." guardada correctamente";
  
  frameGo("fbody",'tasklog_list.php');
  }
else
  echo "Error:  {$n->error}";
  
  
HTML('action_footer');

?>