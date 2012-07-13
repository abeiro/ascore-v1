<?php

require_once("GlobalSche/GlobalSche.php");

$u=newObject("gsteplog");

$u->searchResults=$u->selectAll($offset,$sort);
$d=array(
  "gtask_id"=>$u->get_external_reference("gtask_id"),
  "gtasklog_id"=>$u->get_external_method("gtasklog_id","idname"),
  "gstep_id"=>$u->get_external_reference("gstep_id")
  
);
  //print_r($d);
listList($u,$d,"steplog_list");




?>
