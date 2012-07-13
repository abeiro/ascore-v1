<?php

require_once("Bilo.php");
$REFERER=(empty($REFERER))?$_SERVER["HTTP_REFERER"]:$REFERER;
plantHTML(
array (
	"MSG"=>"Identifíquese",
	"SMSG"=>$SYS["MESSAGES"],
	"REFERER"=>$REFERER,
	"ROOT"=>$SYS["ROOT"]
	
	),
	"Public/public_entrada"
);

?>