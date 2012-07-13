<?php
error_reporting(e_ERROR);

require_once (dirname(__FILE__)."/../../coreg2.php");

foreach($SYS["APPS"] as $apps)
	set_include_dir(dirname(__FILE__)."/../../../Apps/$apps/-");

/*
$jjj=newObject("at_importar_datos");
dataDump($jjj);
*/

if($function = $_GET['function']){
	
	foreach($_GET as $k=>$v){
		if($k!='function' && $k!= 'CL'){
			if(is_numeric($v))
				$args[]= $v;
			else
				$args[]= "'$v'";
		}
	}
	if($args)
		 $args = implode(', ',$args);	

	if($CL = $_GET['CL']){
		$element=newObject($CL);
		eval("\$element->$function($args);");
	}
	else{
		eval("$function($args);");
	}
}

?>