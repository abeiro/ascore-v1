<?php
require_once("Memo.php");
require_once("security.php");

HTML("action_header");
	
echo "Autocompletando";
$anombre=basename($aname);
frameSendAction("fbody","document.getElementById('anombre').value='$anombre'");

HTML("action_footer");


?>