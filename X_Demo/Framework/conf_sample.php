<?php

$SYS["SITENAME"]="asCore Development Branch";


/*
 	PATHS

$SYS[BASE]: 	Path to the class library. It's autodetected if is under main_project_dir/framework 

$SYS[ROOT]: 	Base URL to call. With database parameters is the only thing to conf.
		Put the URL to access your main project, in order that $SYS["ROOT"]."/framework"
		would be the point to access coreg2 current development framework 

*/
		
$SYS["BASE"]=dirname(__FILE__)."/..";					
$SYS["ROOT"]="http://".$_SERVER["SERVER_NAME"].dirname($_SERVER["SCRIPT_NAME"])."/";
//
// 	DATABASE
//
$SYS["DBDRIVER"]=($SYS["DBDRIVER"])?$SYS["DBDRIVER"]:"mysql";
$SYS["mysql"]["DBUSER"]=($SYS["mysql"]["DBUSER"])?$SYS["mysql"]["DBUSER"]:"<DBDBUSER>";
$SYS["mysql"]["DBHOST"]=($SYS["mysql"]["DBHOST"])?$SYS["mysql"]["DBHOST"]:"<DBAHOST>";
$SYS["mysql"]["DBNAME"]=($SYS["mysql"]["DBNAME"])?$SYS["mysql"]["DBNAME"]:"<DBDBNAME>";
$SYS["mysql"]["DBPASS"]=($SYS["mysql"]["DBPASS"])?$SYS["mysql"]["DBPASS"]:"<DBDBPASS>";
$SYS["DBDRIVER_CONFIGURED"]=true;
//
//	Default rows returned by selects
//
$SYS["DEFAULTROWS"]=($SYS["DEFAULTROWS"])?$SYS["DEFAULTROWS"]:15;


//
//	DEBUGGING
//

//This just Enable/Disable Debug Window. See Client/debug_window.sh

if (!isset($TrazaStatus))
	$TrazaStatus=True;

// Enable/Disable Authentication
$SYS["config"]["authentication"]=true;

$SYS["GLOBAL"]["DEV_MODE"]=true;


/* LibZoom: popup or iframe */
//$SYS["config"]["zoom"]="popup"
$SYS["config"]["zoom"]="iframe";

$SYS["bcompiler_extension"]=false;

/* Acces for myself */
$SECRETKEY="<SECRETKEY>";

?>
