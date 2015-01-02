<?php

//error_reporting(E_ERROR);
require_once(dirname(__FILE__)."/../../../coreg2.php");
while (@ob_end_clean());
header("Content-Type: text/javascript");
if (!$_GET["did"])
	die(json_encode("NO"));
unset($_SESSION["desktopaxot"]["panel"][$_GET["did"]]);
unset($_SESSION["permastore"][$_GET["did"]]);
die(json_encode("YES"));

?>