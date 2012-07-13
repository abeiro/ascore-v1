<?php

/* Link, Frame, Label and Variable to check to show */

if (BILO_isAdmin()) {
$menu_entry=array(
"label"=>"Sistema",
"active"=>True,
"items"=>array(
	array("System/action_check_status.php","footer","Estado"),
	array("System/import_sql.php","fbody","Importar Datos"),
	array("System/action_dump.php","footer","BackUp de BBDD"),
	array("System/import_csv.php","fbody","Importar CSV"),
	array("System/action_check_transactions.php","footer","Test de InnoDB"),
	array("System/testme.php","fbody","BenchMark de Sistema"),
	array("System/sanitize_pics.php","fbody","Sanear FotoCatalogo"),
	array("System/sanitize_file.php","fbody","Sanear Repositorio"),
	array("System/info.php","fbody","Info"),
	array("System/sysinfo.php","fbody","Server Info"),
	)
);
}
?>
