<?php

/* 
Demo Modulo for asCore

*/

/* This line establishes current module as "Demo"*/
$SYS["PROJECT"]=($SYS["PROJECT"])?$SYS["PROJECT"]:"Community";

/* This is the require for the asCore API */
require_once("coreg2.php");


/* This will add in include path templae directory and local class directory */
set_include_dir(dirname(__FILE__)."/local/Tmpl/-");
set_include_dir(dirname(__FILE__)."/-");

?>
