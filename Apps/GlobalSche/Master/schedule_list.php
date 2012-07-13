<?php

require_once("GlobalSche/GlobalSche.php");

$u=newObject("schedule");

$u->searchResults=$u->selectAll($offset,$sort);
$d=array();

listList($u,$d,"schedule_list");




?>
