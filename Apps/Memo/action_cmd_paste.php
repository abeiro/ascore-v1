<?php

require_once("Memo.php");
require_once("security.php");

HTML("action_header");
	

$e2del=explode(",",$IDS);
echo "Copiando...";
if ($_SESSION["memo"]["clipboard"]["action"]=="copy") {
	foreach ($_SESSION["memo"]["clipboard"]["ID"] as $k=>$v) {
		
		$orig=newObject("data_object",$v);
			if (checkReadSecurity($v)) {
			echo " {$orig->nombre}";
			$new=newObject("data_object",1);
			if (!$new->save_copy($orig,$inode))
				echo " <strong>".$new->ERROR."</strong> ";
		}
		else echo "Denegado";
	}
}
else if ($_SESSION["memo"]["clipboard"]["action"]=="cut") {
	foreach ($_SESSION["memo"]["clipboard"]["ID"] as $k=>$v) {
		if (checkSecurity($v)) {
			$orig=newObject("data_object",$v);
			$orig->inode=$inode;	
			$orig->save();
			echo $orig->ERROR;
		}
	}
}

unset($_SESSION["memo"]["clipboard"]);




frameReload("fbody");
HTML("action_footer");



?>