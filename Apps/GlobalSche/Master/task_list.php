<?php

require_once("GlobalSche/GlobalSche.php");

$u=newObject("gtask");

setLimitRows(1000);
$u->searchResults=$u->selectAll($offset,$sort);
$d=array(
  "schedule_id"=>$u->get_external_reference("schedule_id")
);

listList($u,$d,"task_list");
resetLimitRows();



?>

