<?php


$SYS["PROJECT"]="System";
require_once("coreg2.php");
if (!BILO_isAdmin())
	die("Sin privilegios");
set_include_dir(dirname(__FILE__)."/local/Tmpl/-");


?>
