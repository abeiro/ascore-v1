<?php

require_once("JasperReports.php");


$aux->isAdmin=BILO_isAdmin();

$aux=newObject("reportparams");

setLimitRows(20);
$aux->searchResults=$aux->selectAll($offset,$sort);

$external_data=array(
		"jasperreport_id"=>$aux->get_external_reference("jasperreport_id")
);

listList($aux,$external_data,"listadoreportparams");

resetLimitRows();



?>
