<?php

/*
 * $session_save_path = "localhost:11211";
ini_set('session.save_handler', 'memcached');
ini_set('session.save_path', $session_save_path);


ini_set('session.save_handler', 'memcache');
ini_set('session.save_path', "tcp://127.0.0.1:11211");

 */
debug("Timestamp: ". (getmicrotime()-$GLOBALS["CODEINITTIME"])." ".__FILE__." ".__LINE__,"green");
if (!ini_set("session.use_trans_sid","0")) {
		debug("Problema: session.use_trans_sid","red");
		
		}

if (!ini_set("session.use_only_cookies","1"))
		debug("Problema: session.use_only_cookies ko","red");

if (!ini_set("session.name","ascore"))
	echo "session.name ko";

$PATH=dirname($_SERVER["SCRIPT_NAME"]);
$DOMAIN=$_SERVER["SERVER_NAME"];

session_set_cookie_params ( 0 , $PATH,'',0);
debug("Cookie params: ".ini_get('session.cookie_path')." ".ini_get('session.name')." ".$DOMAIN,"red");
if (!session_start())
	$debug("No se pudo iniciar la sesion con cookie","red");


debug("Timestamp: ". (getmicrotime()-$GLOBALS["CODEINITTIME"])." ".__FILE__." ".__LINE__,"green");
	
?>
