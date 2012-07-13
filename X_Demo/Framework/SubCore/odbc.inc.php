<?php



$dbhost=$SYS["odbc"]["DBHOST"];
$dbuser=$SYS["odbc"]["DBUSER"];
$dbpass=$SYS["odbc"]["DBPASS"];
$dbname=$SYS["odbc"]["DBNAME"];


if ($ODBC_ID=odbc_pconnect($dbname,$dbuser, $dbpass))
	debug(_("ODBC Driver: Connected succesfully"),"green");
else
	die(_("ODBC Driver: Connection Failed"));


function _query ($q,$multi=False) {
	
	global $ODBC_ID,$last_odbc_result;

	if ($multi) {
		$sentences=array();
		$sentences=explode(";",$q);

		for ($i=0;$i<sizeof($sentences);$i++) {
			$line=trim($sentences[$i]);
			if (empty($line))
				continue;
			$bdres=odbc_exec($ODBC_ID,$sentences[$i]) or die(debug(_("query failed:")." [\"".$sentences[$i]."\"]","red"));

		}
	}

	else
	{
	$bdres=odbc_exec($ODBC_ID,$q) or die(debug(_("query failed:")."[\"$q\"]","red"));

        }
	$last_odbc_result=$bdres;
	debug(" SQL: ".$q." Rows:".odbc_num_rows($bdres),"green");
	return $bdres;

}


function _fetch_array($bdid){

	global $ODBC_ID,$last_odbc_result;
	
	
	
	  if (odbc_fetch_into($bdid,$res_odbc_row)) {
	  	
		foreach ($res_odbc_row as $k=>$v)
			$data_odbc_row[odbc_field_name($bdid,$k+1)]=$v;
	  	return $data_odbc_row;
	 }
	 else 
	 	return False;
		  
}

function _last_id() {

	global $ODBC_ID,$last_odbc_result;
	
	return false;

}

function _affected_rows() {
	
	global $ODBC_ID,$last_odbc_result;
	if (odbc_num_rows($last_odbc_result)!=-1)
		return odbc_num_rows($last_odbc_result);
	else
		return 0;
	
}





?>
