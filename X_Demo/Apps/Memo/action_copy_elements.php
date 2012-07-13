<?php
require_once("Memo.php");
require_once("security.php");

HTML("action_header");
	

$e2del=explode(",",$IDS);
unset($_SESSION["memo"]["clipboard"]);
$_SESSION["memo"]["clipboard"]["action"]="copy";

foreach ($e2del as $k=>$v) {
	if (!empty($v)) {
		$_SESSION["memo"]["clipboard"]["ID"][]=$v;
	}

}
echo "".(sizeof($e2del)-1)." elemento(s) copiado(s)";


HTML("action_footer");


?>