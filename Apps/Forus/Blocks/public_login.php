<?php

require_once("Bilo.php");
$REFERER=(empty($REFERER))?$_SERVER["HTTP_REFERER"]:$REFERER;
plantHTML(
array (
	"MSG"=>"Identif�quese",
	"SMSG"=>$SYS["MESSAGES"],
	"REFERER"=>$REFERER,
	"ROOT"=>$SYS["ROOT"]
	
	),
	"Public/public_entrada"
);

?>