<?php

require_once("JasperReports.php");


$u=newObject("jasperreport");

$u->isAdmin=BILO_isAdmin();
$d=array(
	"isPrint"=>"fref#report|ID|Is_Print"
);

setLimitRows(15000);
$u->searchResults=$u->selectAll($offset,$sort);
resetLimitRows();

listList($u,$d,"list_reports");




?>


