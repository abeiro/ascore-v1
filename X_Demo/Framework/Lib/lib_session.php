<?php

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
	$debug("No se pudo iniciar la sesión con cookie","red");


	
?>
