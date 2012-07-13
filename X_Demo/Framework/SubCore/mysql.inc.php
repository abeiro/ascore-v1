<?php



$dbhost=$SYS["mysql"]["DBHOST"];
$dbuser=$SYS["mysql"]["DBUSER"];
$dbpass=$SYS["mysql"]["DBPASS"];
$dbname=$SYS["mysql"]["DBNAME"];


mysql_pconnect("$dbhost","$dbuser","$dbpass") or debug(_("MySQL Driver: Failed pconnect"),"red");
mysql_select_db("$dbname") or debug(_("MySQL Driver: Failed select"),"red");
debug(_("MySQL Driver: Connected succesfully").":encoding: ".mysql_client_encoding(),"green");
if (function_exists("mysql_set_charset"))
	mysql_set_charset("utf8") or debug(_("Could'n set charset UTF-8"));
else
	mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'") or debug(_("Could'n set charset UTF-8"));
debug(_("MySQL Driver: charset:").mysql_client_encoding(),"green");

function _query ($q,$multi=False) {
	


	if ($multi) {
		$sentences=array();
		$sentences=explode(";",$q);

		for ($i=0;$i<sizeof($sentences);$i++) {
			$line=trim($sentences[$i]);
			if (empty($line))
				continue;
			$bdres=mysql_query($sentences[$i]) or die(debug("query falló: [\"".$sentences[$i]."\"]".mysql_error(),"red"));

		}
	}

	else
	{
	$bdres=mysql_query($q) or die(debug("query falló: [\"$q\"]".mysql_error(),"red"));

    }
	debug(" SQL: ".$q." Rows:".mysql_affected_rows(),"green");
	return $bdres;

}


function _fetch_array($bdid){

	return mysql_fetch_assoc($bdid);
}

function _last_id() {
	return mysql_insert_id();

}

function _affected_rows() {

	return mysql_affected_rows();
}





?>
