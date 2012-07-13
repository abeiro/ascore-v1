<?php
require_once("GlobalSche/GlobalSche.php");



$ID=(isset($ID))?$ID:1;
if($ID>1)
  $n=newObject("schedule",$ID);
else
  $n=newObject("schedule");

$fkeys=array();

formAction("action_save_schedule.php?ID=$ID","footer","editForm");
$n->_boton0=gfxBotonAction("Guardar","getElementById('editForm').submit()",True);

plantHTML($n,'schedule_add',$fkeys);
formClose();

?>