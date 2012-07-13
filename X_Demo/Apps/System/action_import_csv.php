<?php
require_once("coreg2.php");
debug("Import elements from CSV","white");
foreach ($SYS["APPS"] as $app)
	set_include_dir($SYS["BASE"]."/Apps/".$app."/.");
	
$data=file($_FILES["fichero_csv"]["tmp_name"]);
$FIELDS=explode(";",current($data));
foreach ($FIELDS as $a=>$b)
	$FIELDS[$a]=strtolower(trim(strtr($b,"\"'","  ")));

dataDump($FIELDS);
foreach($data as $k=>$v) {
	if ($k!=0) {
		$DATAFIELDS=explode(";",$v);
		foreach ($DATAFIELDS as $a=>$b)
			$DATAFIELDS[$a]=strtr($b,"\"'","");
		$q="INSERT INTO {$prefix}_{$table} (`".implode("`,`",$FIELDS)."`) VALUES (".implode(",",$DATAFIELDS).")";
		_query($q);
	}

}

?>