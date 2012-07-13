<?php

require_once("coreg2.php");

if (empty($command))
	$command="listdef";

if (file_exists($SYS["DOCROOT"]."/Framework/cmd_".$command.".php"))
	include($SYS["DOCROOT"]."/Framework/cmd_".$command.".php");
else
	echo $SYS["DOCROOT"]."/Framework/cmd_".$command.".php no encontrado";




?>
