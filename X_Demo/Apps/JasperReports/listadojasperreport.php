<?php

require_once("JasperReports.php");

$aux->isAdmin=BILO_isAdmin();

$aux=newObject("jasperreport");

setLimitRows(20);
$aux->searchResults=$aux->selectAll($offset,$sort);

listList($aux,array(),"listadojasperreport");

resetLimitRows();



?>
