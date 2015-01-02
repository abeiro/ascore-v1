<?php
error_reporting(E_ERROR);
require_once(dirname(__FILE__)."/../../../coreg2.php");
while (@ob_end_clean());
header("Content-Type: application/excel");
header('Content-Disposition: attachment; filename="data.xlsx"');

require_once("Lib/lib_xlsxwriter.class.php");



foreach (json_decode($_SESSION["cacheexpordata"][$_GET["uid"]]) as $nline=>$line){
	foreach ($line as $field) 
		if (strlen($field)<100)
				$data[$nline][]=$field;
		else
				$data[$nline][]="";
	
}

$writer = new XLSXWriter();
$writer->writeSheet($data);
$writer->writeToStdOut();
?>