<?php

require_once("GlobalSche/GlobalSche.php");

$u=newObject("gtasklog");

$u->searchResults=$u->selectAll($offset,$sort);
$d=array(
  "gtask_id"=>$u->get_external_reference("gtask_id"),
  "schedule_id"=>$u->get_external_reference("schedule_id")
);

listList($u,$d,"gtasklog_list");


?>
