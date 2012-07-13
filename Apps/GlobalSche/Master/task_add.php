<?php
require_once("GlobalSche/GlobalSche.php");



$ID=(isset($ID))?$ID:1;
if($ID>1)
  $n=newObject("gtask",$ID);
else
  $n=newObject("gtask");
  
  
$fkeys=array("schedule_id"=>$n->get_references("schedule_id"));
formAction("action_save_task.php?ID=$ID","footer","editForm");
$n->_boton0=gfxBotonAction("Guardar","getElementById('editForm').submit()",True);

plantHTML($n,'task_add',$fkeys);
formClose();

?>