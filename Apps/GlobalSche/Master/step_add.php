<?php
require_once("GlobalSche/GlobalSche.php");



$ID=(isset($ID))?$ID:1;
if($ID>1)
  $n=newObject("gstep",$ID);
else
  $n=newObject("gstep");

$fkeys=array("gtask_id"=>$n->get_references("gtask_id"));
//print_r($fkeys);  
formAction("action_save_step.php?ID=$ID","footer","editForm");
$n->_boton0=gfxBotonAction("Guardar","getElementById('editForm').submit()",True);

plantHTML($n,'step_add',$fkeys);
formClose();

?>