<?php

define("DBOPERAND_ILIKE","LIKE");

$dbhost=$SYS["mysql"]["DBHOST"];
$dbuser=$SYS["mysql"]["DBUSER"];
$dbpass=$SYS["mysql"]["DBPASS"];
$dbname=$SYS["mysql"]["DBNAME"];


$GLOBALS["MYSQL_DBLINK"];


$GLOBALS["MYSQL_DBLINK"]=mysqli_connect("p:$dbhost","$dbuser","$dbpass") 
	or nodebug(_("MySQL Driver: Failed pconnect: ".mysqli_error()),"red");

mysqli_select_db($GLOBALS["MYSQL_DBLINK"],"$dbname") or nodebug(_("MySQL Driver: Failed select"),"red");
nodebug(_("MySQL Driver: Connected succesfully").":encoding: ".mysqli_character_set_name($GLOBALS["MYSQL_DBLINK"]),"green");
if (function_exists("mysqli_set_charset"))
	mysqli_set_charset($GLOBALS["MYSQL_DBLINK"],"utf8") or nodebug(_("Could'n set charset UTF-8"));
else
	mysqli_query($GLOBALS["MYSQL_DBLINK"],"SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'") or nodebug(_("Could'n set charset UTF-8"));
nodebug(_("MySQL Driver: charset:").mysqli_character_set_name($GLOBALS["MYSQL_DBLINK"]),"green");

function _query ($q,$multi=False) {
	
	nodebug("Timestamp: ". (getmicrotime()-$GLOBALS["CODEINITTIME"])." ".__FILE__." ".__LINE__,"green");
    nodebug(" PRESQL: <$q>","green");
	if ($multi) {
		$sentences=array();
		$sentences=explode(";",$q);

		for ($i=0;$i<sizeof($sentences);$i++) {
			$line=trim($sentences[$i]);
			if (empty($line))
				continue;
			$bdres=mysqli_query($GLOBALS["MYSQL_DBLINK"],$sentences[$i]) or die(debug("query failed: [\"".$sentences[$i]."\"]".mysql_error($GLOBALS["MYSQL_DBLINK"]),"red"));

		}
	}

	else
	{
	$bdres=mysqli_query($GLOBALS["MYSQL_DBLINK"],$q) or die(debug("query failed: [\"$q\"]".mysqli_error($GLOBALS["MYSQL_DBLINK"]),"red"));

    }
	debug(" SQL: ".$q." Rows:".mysqli_affected_rows($GLOBALS["MYSQL_DBLINK"]),"green");
	nodebug("Timestamp: ". (getmicrotime()-$GLOBALS["CODEINITTIME"])." ".__FILE__." ".__LINE__,"green");
	return $bdres;

}


function _fetch_array($bdid){

	return mysqli_fetch_assoc($bdid);
}

function _last_id() {
	return mysqli_insert_id($GLOBALS["MYSQL_DBLINK"]);

}

function _affected_rows($bdid=false) {

    if ($bdid) {
        return mysqli_num_rows($bdid);
    } else {
        return mysqli_affected_rows($GLOBALS["MYSQL_DBLINK"]);
    }
	
}





?>
