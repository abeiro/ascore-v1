<?php

/* Link, Frame, Label and Variable to check to show */
if(BILO_isAdmin()) {
$menu_entry=array(
"label"=>"Informes Jasper",
"active"=>True,
"items"=>array(
	array("JasperReports/index.php","fbody","Informes disponibles"),
	array("JasperReports/add.php","fbody","Nuevo informe"),
	array("JasperReports/dev.php?command=querybuilder","fbody","Constructor de consultas"),
	array("JasperReports/interface.php?name=jasperreport","fbody","Gestión"),
	array("JasperReports/test.php","fbody","Chequear subsistema JAVA"),
	array(
			array(
				array("JasperReports/listadojasperreport.php","fbody","Jaspers"),
				array("JasperReports/nuevo_jasperreport.php","fbody","Nuevo Jaspers"),
				array("JasperReports/listadoreportparams.php","fbody","Params"),
				array("JasperReports/nuevo_reportparams.php","fbody","Nuevo Params"),
			),
			"Maestros",
			"Maestros"
			)
	)
);

} 

?>