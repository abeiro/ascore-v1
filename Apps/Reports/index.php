<?php

require_once("Reports.php");


$u=newObject("report");

$u->isAdmin=BILO_isAdmin();
$d=array(
	"isPrint"=>"fref#report|ID|Is_Print"
);
	
$u->searchResults=$u->selectAll($offset,$sort);

listList($u,$d,"list_reports");




?>


