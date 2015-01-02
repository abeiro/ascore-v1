<?php
error_reporting(E_ERROR);
require_once(dirname(__FILE__)."/../../../coreg2.php");

if (!$_GET["did"])
	die();
if ((time()-$_SESSION["desktopaxot"]["ttl"][$_GET["did"]])>900) {
	header('HTTP/1.0 200');
}
else {
	$_SESSION["desktopaxot"]["ttl"][$_GET["did"]]=time();
	header('HTTP/1.0 404 Not Found');
}

?>