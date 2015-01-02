<?php

/* Link, Frame, Label and Variable to check to show */

if (BILO_isAdmin()) {
$menu_entry=array(
"label"=>_("System"),
"active"=>True,
"items"=>array(
	array("System/action_check_status.php","footer","Status"),
	array("System/import_sql.php","fbody","Data Load"),
	array("System/action_dump.php","footer","DB Backup"),
	array("System/import_csv.php","fbody","CSV import"),
	array("System/action_check_transactions.php","footer","InnoDB test"),
	array("System/testme.php","fbody","System benchmark"),
	//array("System/sanitize_pics.php","fbody","Sanear FotoCatalogo"),
	array("System/sanitize_file.php","fbody","Sanitice file repository (Memo)"),
	array("System/info.php","fbody","Info"),

	)
);
}
?>
