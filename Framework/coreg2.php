<?php

error_reporting(E_ALL ^ E_NOTICE);
ini_set("include_path",".:".dirname(__FILE__)."/../Framework/:".dirname(__FILE__)."/../Apps/");

define("_COREVER","0.99.9");


/********* SECTION 0:		MAIN CONFIGURATION 

SYS[DATADEFPATH]: Where the classes are
		
*********************************************/

require_once "conf.php";

$SYS["DOCROOT"]=$SYS["BASE"]."/Framework/";
$SYS["COREROOT"]=$SYS["BASE"]."/Framework/";
$SYS["URL"]=$SYS["ROOT"]."/Framework/";
$SYS["COREURL"]=$SYS["ROOT"]."/Framework/";
$SYS["DATADEFPATH"]="Class/";
$SYS["ASCACHEDIR"]=md5(__FILE__);
if ($SYS["bcompiler_extension"]) {
if (file_exists($SYS["DOCROOT"]."/Data/bcompiler.so"))  {
	if (!dl("../../../../".$SYS["DOCROOT"]."/Data/bcompiler.so")) 
		$SYS["bcompiler_extension"]=false;
}
else 
	$SYS["bcompiler_extension"]=false;


}





/******** SECTION 1: 		INCLUDE PATH PATCH

Include Patch. For a strange reason, Apache on Hasefroch Winblows seems to use double dot to join 
differents PATHS This VNH - Very Nasty Hack (TM) - solves it

Nana Solved it.
*********************************************/


define ('_PATH_SYMBOL',PATH_SEPARATOR );




/******** SECTION 3: 		INCLUDE PATH AND INCLUDES

Now we can set some configuration PATHS  and load
main classes

*********************************************/

ini_set("include_path","."._PATH_SYMBOL.dirname(__FILE__)._PATH_SYMBOL.$SYS["BASE"]._PATH_SYMBOL.$SYS["BASE"]."/".$SYS["PROJECT"]._PATH_SYMBOL.ini_get("include_path"));

ini_set("include_path","."._PATH_SYMBOL.$SYS["BASE"]."/Framework/Plantillas/"._PATH_SYMBOL.ini_get("include_path"));
ini_set("include_path","."._PATH_SYMBOL.$SYS["BASE"]."/Themes/Default/Tmpl/"._PATH_SYMBOL.ini_get("include_path"));

ini_set("session.save_path",dirname(__FILE__)."/../Sessions");

include("SubCore/core.php");			// Core main class

debug("Timestamp: ". (getmicrotime()-$GLOBALS["CODEINITTIME"])." ".__FILE__." ".__LINE__,"green");



/********* SECTION 4:		MISC CONFIGURATIONS

Some other parameters you can touch

SYS[LANG]:		Locale setting. This will enable xgettext support also.
SYS[PROJECT]:		Custom project include and settings
SYS["NAVVARS"]:		Vars to preserve between search pages
prefix:			Prefix of tables in database. (Useful to integrate with PHPNuke & Horde)
**********************************************/

$SYS["LANG"]="es";
$SYS["PROJECT"]=(!isset($SYS["PROJECT"])) ? "" : $SYS["PROJECT"];
if (!empty($SYS["PROJECT"]))
	require_once $SYS["PROJECT"]."/".$SYS["PROJECT"].".php";
else
	require_once "project.php";



$SYS["NAVVARS"]=array("offset","sort");

$prefix=(!isset($prefix))?"coreg2":$prefix;	



/********* SECTION 5:		DATABASE CONFIGURATION

Choose a backend and put here the configuration
You also may want to put the configuration parameters
under $SYS["PROJECT"].".php". Values will not be overriden
by these below.

**********************************************/


$SYS["DBDRIVER"]=(!isset($SYS["DBDRIVER"])) ? "mysql" :$SYS["DBDRIVER"];

if (!isset($SYS["DBDRIVER_CONFIGURED"])) {

	if ($SYS["DBDRIVER"]=="mysql") {
		
		$SYS["mysql"]["DBUSER"]="";
		$SYS["mysql"]["DBHOST"]="";
		$SYS["mysql"]["DBNAME"]="";
		$SYS["mysql"]["DBPASS"]="";
	}
	else if ($SYS["DBDRIVER"]=="odbc") {
		/* This one is for ODBC */
		$SYS[$SYS["DBDRIVER"]]["DBUSER"]="UID";
		$SYS[$SYS["DBDRIVER"]]["DBHOST"]="";
		$SYS[$SYS["DBDRIVER"]]["DBNAME"]="DSN";
		$SYS[$SYS["DBDRIVER"]]["DBPASS"]="PWD";
	
	}
	
	else if ($SYS["DBDRIVER"]=="dbtcp") {
		/* This one is for DBTCP */
		$SYS[$SYS["DBDRIVER"]]["DBUSER"]="";
		$SYS[$SYS["DBDRIVER"]]["DBHOST"]="server_host";
		$SYS[$SYS["DBDRIVER"]]["DBNAME"]="DSN=DNS_NAME;UID=;PWD=;";
		$SYS[$SYS["DBDRIVER"]]["DBPASS"]="";
	}
	
	
}





/********* SECTION 6:		INCLUDES AND ADD-ONS

Includes. You may comment some Libs if you want.
Do not comment ant SubCore include

****************************************************/

include("SubCore/".$SYS["DBDRIVER"].".inc.php");// DB driver
include("SubCore/root.php");			// Root class, root of evil ;)

debug("Timestamp: ". (getmicrotime()-$GLOBALS["CODEINITTIME"])." ".__FILE__." ".__LINE__,"green");

/* Includes : Libraries */

include("Lib/lib_planty.php");				// Template library
include("Lib/lib_list.php");				// Listing library
include("Lib/lib_debug.php");				// Debug library
include("Lib/lib_date.php");				// Dating library ;)
require("Lib/lib_frame.php");				// Frame library
require("Lib/lib_gfx.php");					// GFX library
require("Lib/lib_session.php");				// Session library
require("Lib/lib_zoom.php");				// Session library
require("Lib/lib_monitor.php");				// Session library
require("Lib/lib_class_xml_to_array.php");              // Aditional XML library

/* fileh class Initializer. VNH Soon to be removed*/
$SYS["thumbsize"]="128";
define ("CALIDAD_JPG",85);


/********* SECTION 7:		POWER FUNCTIONS

Some addons of later addition

Compiled properties support:

	Serializes classes once loaded from XML. Then
	stores on disk (session_save_path is used).
	Next time is directly loaded from disk instead
	of parsing again from XML. Automagically detects
	changes on XML files and recompiles.
	This can reduce server load and execution time
	by 1/2. You may disable SYS[CACHE_TIMESTAMP]
        once project is finished, so system will not bother
	about recompile XML classes.
****************************************************/

/* Compiled properties support */
if (!is_dir(session_save_path()."/coreg2_cache/{$SYS["ASCACHEDIR"]}")) {
	mkdir(session_save_path()."/coreg2_cache");
	mkdir(session_save_path()."/coreg2_cache/{$SYS["ASCACHEDIR"]}");
}
$SYS["CACHE_TIMESTAMP"]=True;


/* Some other INIT Stuff */
if ($_SERVER["SERVER_PORT"]==80) {
	$SYS["PROTO"]="http://";
}


debug("Timestamp: ". (getmicrotime()-$GLOBALS["CODEINITTIME"])." ".__FILE__." ".__LINE__,"green");
require("post-init.php");
?>
