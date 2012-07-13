<?php

$SYS["PROJECT"]=($SYS["PROJECT"])?$SYS["PROJECT"]:"Noticias";
require_once("coreg2.php");
set_include_dir(dirname(__FILE__)."/local/Tmpl/-");
set_include_dir(dirname(__FILE__)."/-");

define(_NOTICIAS,100);
$FAMILYLABEL[_NOTICIAS]="Noticias";
$SYS["thumbsize"]="320";

?>