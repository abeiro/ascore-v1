<?php

/* Bloques */
/********** Construccion de la portada */

set_include_dir(dirname(__FILE__)."/local/Tmpl/-");
set_include_dir(dirname(__FILE__)."/-");

$SITEMAP='asCore Development Branch :: Demo';
$DESCRIPTION='asCore Development Branch :: Demo';
$KEYWORDS='ascore';

ob_start();
global $SYS;
$APP=(empty($APP))?"Introduccion":$APP;
if (file_exists(dirname(__FILE__)."/action_$APP.php"))
	require(dirname(__FILE__)."/action_$APP.php");


$data=ob_get_contents();
ob_end_clean();
	
plantHTML(
	array(
		"ROOT"=>$SYS["ROOT"],
		"SITEMAP"=>"$SITEMAP",
		"DESCRIPTION"=>$DESCRIPTION,
		"KEYWORDS"=>$KEYWORDS,
		"fecha"=>time(),
		"mainview"=>$data
		),
	"Demo");
	

	
	
?>