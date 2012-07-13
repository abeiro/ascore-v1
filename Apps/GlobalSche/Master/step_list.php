<?php

require_once("GlobalSche/GlobalSche.php");

$u=newObject("gstep");

$u->searchResults=$u->selectAll($offset,$sort);
$d=array(
  "gtask_id"=>$u->get_external_reference("gtask_id")
);

listList($u,$d,"step_list");




?>
