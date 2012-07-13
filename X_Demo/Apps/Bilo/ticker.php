<?php

require_once("Bilo.php");

formAction("action_ticker.php","footer","editForm");
	plantHTML(
	array (
		"MSG"=>"Entrada al Sistema",
		"SMSG"=>$SYS["MESSAGES"],
		"REFERER"=>$_SERVER["HTTP_REFERER"]
		),"ticker");
formClose()


?> 