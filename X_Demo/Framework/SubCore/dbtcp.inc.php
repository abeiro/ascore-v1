<?php



$dbhost=$SYS["dbtcp"]["DBHOST"];
$dbuser=$SYS["dbtcp"]["DBUSER"];
$dbpass=$SYS["dbtcp"]["DBPASS"];
$dbname=$SYS["dbtcp"]["DBNAME"];


if ($DBTCP_ID=dbtcp_connect($dbname,$dbhost, 3000))
	debug(_("DBTCP Driver: Connected succesfully"),"green");
else
	die(_("DBTCP Driver: Connection Failed"));


function _query ($q,$multi=False) {
	
	global $DBTCP_ID,$dbtcp_num_rows_last_resut;

	if ($multi) {
		$sentences=array();
		$sentences=explode(";",$q);

		for ($i=0;$i<sizeof($sentences);$i++) {
			$line=trim($sentences[$i]);
			if (empty($line))
				continue;
			$bdres=dbtcp_sql($sentences[$i],$DBTCP_ID) or die(debug(_("query failed:")." [\"".$sentences[$i]."\"]".dbtcp_error(),"red"));

		}
	}

	else
	{
	$bdres=dbtcp_sql($q,$DBTCP_ID) or die(debug(_("query failed:")."[\"$q\"]".dbtcp_error(),"red"));
	$bdres=dbtcp_sql("SELECT FOUND_ROWS()",$DBTCP_ID);
	$res=dbtcp_fetch($DBTCP_ID);
	$bdres=dbtcp_sql($q,$DBTCP_ID) or die(debug(_("query failed:")."[\"$q\"]".dbtcp_error(),"red"));
	

        }
	$dbtcp_num_rows_last_resut=$res[0];
	debug(" SQL: ".$q." Rows:".$dbtcp_num_rows_last_resut,"green");
	return $bdres;

}


function _fetch_array($bdid){

	global $DBTCP_ID,$dbtcp_num_rows_last_resut;
	
	
	return dbtcp_fetch_assoc($DBTCP_ID);
}

function _last_id() {

	global $DBTCP_ID,$dbtcp_num_rows_last_resut;
	
	$bdres=dbtcp_sql("SLAST_INSERT_ID()",$DBTCP_ID);
	$res=dbtcp_fetch($DBTCP_ID);
	return $res[0];

}

function _affected_rows() {
	
	global $DBTCP_ID,$dbtcp_num_rows_last_resut;
	
	return $dbtcp_num_rows_last_resut;
	
	
}





?>
