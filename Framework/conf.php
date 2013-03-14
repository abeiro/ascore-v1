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
$SYS["mysql"]["DBUSER"]=($SYS["mysql"]["DBUSER"])?$SYS["mysql"]["DBUSER"]:"ascore";
$SYS["mysql"]["DBHOST"]=($SYS["mysql"]["DBHOST"])?$SYS["mysql"]["DBHOST"]:"localhost";
$SYS["mysql"]["DBNAME"]=($SYS["mysql"]["DBNAME"])?$SYS["mysql"]["DBNAME"]:"ascore_dev";
$SYS["mysql"]["DBPASS"]=($SYS["mysql"]["DBPASS"])?$SYS["mysql"]["DBPASS"]:"test";
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
$SECRETKEY="6e3fa0aa75e90b271f7688500c3bd690";

// Active Directory
   
$SYS["AUTH"]["activedirectory"]["server"]="172.24.81.1";
$SYS["AUTH"]["activedirectory"]["basedn"]="DC=crs3187,DC=rural";
$SYS["AUTH"]["activedirectory"]["domain"]="crs3187";
$SYS["AUTH"]["activedirectory"]["searchdn"]=array(
      "CN=Users",
      "OU=Usuarios",
      "OU=Administrativos,OU=Usuarios");
 

$SYS["admin_email"]="operador@helpdesk.ruralsur.es";
$SYS["admin_realm"]="Agente de Planificación";


?>
