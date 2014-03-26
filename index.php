<?php
/*******************************************************************************
CoreG2 - Based portal
********************************************************************************/

/*********************** 
Extended URI featuring

MODULE
APP
ACTION
************************/

error_reporting(E_ERROR & E_DEPRECATED);
ini_set("include_path",".:".dirname(__FILE__)."/Framework/:".dirname(__FILE__)."/Apps/");

extract($_GET);
extract($_POST);
require_once("coreg2.php");


debug("Timestamp: ". (getmicrotime()-$GLOBALS["CODEINITTIME"])." ".__FILE__." ".__LINE__,"green");

ob_start();
$petition=$_GET["petition"];

$PET=preg_replace("/^\/./","",$petition);
debug("EURI Petition: $PET from $petition (".implode(":",$_GET).")","white");
;
if ($PET==="index.php")
	$PET="";
	
if (is_file($PET)) {
	debug("EURI Petition: Direct Access ...... ","white");	
	if ((strpos($PET,"Apps")!==false)&&(strpos($PET,"public")===false)) {
		if (md5($SECRETKEY)!=$PSECRETKEY)
			die("Not allowed");
	}	
	debug("EURI Petition: Direct Access GRANTED ","white");	
	include($PET);
	die();
	}
else{	
	$EURI=explode("/",$PET);
	$MODULE=(empty($EURI[0]))?".":$EURI[0];
	$APP=$EURI[1];
	$ACTION=$EURI[2];
}

if ((!is_file($MODULE."/main.php"))&&(!is_file("X_".$MODULE."/main.php"))) {
	$ACTION="$APP";
	$APP="$MODULE";
	$MODULE=$GLOBALS["SYS"]["defaultmodule"];
	debug("CHANGED! Module $MODULE APP $APP ACTION $ACTION ");
}

debug("EURI Petition: Module $MODULE APP $APP ACTION $ACTION","white");
if ((is_file($MODULE."/main.php"))||(is_file("X_".$MODULE."/main.php"))) {
	$EURI_PARSER=$EURI;
	unset($EURI_PARSER[0]);
	unset($EURI_PARSER[1]);
	foreach ($EURI_PARSER as $key=>$value) {
		$XXX=explode("=",$value);
		$GVARS[$XXX[0]]=$XXX[1];
	}
	if (!empty($GVARS))
		extract($GVARS);
		
	/* Carrito check */	
	$MODULE=($MODULE=="Backend")?"Backend":"X_".$MODULE;
	$MODULE=($MODULE=="X_Ide")?"Ide":$MODULE;
	$MODULE=($MODULE=="X_.")?".":$MODULE;
        
        debug("Timestamp: ". (getmicrotime()-$GLOBALS["CODEINITTIME"])." ".__FILE__." ".__LINE__,"green");
        
        try {
            require($MODULE."/main.php");
        } catch (Exception $e) {
            echo($e);
        }
	
		
	$THIS_PAGE=ob_get_contents();
	ob_end_clean();

	if ($MODULE!="Backend") {
		
		$FINAL_DATA= preg_replace("'<!--[\/\!]*?[^<>]*?-->'si","",javascript_collector(css_collector($THIS_PAGE)));
		
	}
	else
		$FINAL_DATA=ltrim($THIS_PAGE);
	
        
	echo $FINAL_DATA;
        
}
else {
	header("HTTP/1.0 404 Not Found");
	plantHTML($SYS,"Public/pagina_no_encontrada");
}

/*if ($SYS["GLOBAL"]["DEV_MODE"]===true) {
	$fval=fopen("/tmp/.validator_gccv_".$_SERVER["REMOTE_ADDR"],"w");
	fwrite($fval,$FINAL_DATA,strlen($FINAL_DATA));
	fclose($fval);
}*/
?>
