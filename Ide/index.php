<?php
require_once("coreg2.php");
$SYS["PROJECT"]="Ide";

set_include_dir(dirname(__FILE__)."/local/Tmpl/-");
set_include_dir(dirname(__FILE__)."/-");
set_include_dir(dirname(__FILE__)."/../Framework/Extensions/xajax/-");

plantHTML(array(
		"CODEMIRROR_ROOT"=>"{$SYS["ROOT"]}../Framework/Extensions/codemirror/",
		"ROOT"=>"{$SYS["ROOT"]}.."
	),"Header");

?>
