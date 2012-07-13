<?php
require_once("GlobalSche/GlobalSche.php");



$ID=(isset($ID))?$ID:1;
if($ID>1)
  $n=newObject("gtasklog",$ID);
else
  $n=newObject("gtasklog");

$fkeys=array(
  "gtask_id"=>$n->get_references("gtask_id"),
  "schedule_id"=>$n->get_references("schedule_id"),
);
//print_r($fkeys);  
formAction("action_save_tasklog.php?ID=$ID","footer","editForm");
$n->_boton0=gfxBotonAction("Guardar","getElementById('editForm').submit()",True);

plantHTML($n,'gtasklog_add',$fkeys);
formClose();

?>