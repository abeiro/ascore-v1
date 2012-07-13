<?php
require_once("GlobalSche/GlobalSche.php");
plantHTML(array(),'action_header');
$ID=(isset($ID))?$ID:1;
if($ID>1)
  $n=newObject("gsteplog",$ID);
else 
  exit(0);
  

  if ($_GET["cmd"]=="ini") {
   $n->SetStatus("ini");
  } else if ($_GET["cmd"]=="can") {
   $n->SetStatus("can");
  } else if ($_GET["cmd"]=="ter") {
   $n->SetStatus("ter");
  }
  
  
if(!$n->error) {
  echo "Registro actualizado correctamente";
  frameGo("fbody",'steplog_list.php');
  }
else
  echo "Error:  {$n->error}";
  
  
HTML('action_footer');

?>