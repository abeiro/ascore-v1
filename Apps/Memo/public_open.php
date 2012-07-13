<?php
$NOCOMPRESS=true;
require_once("Memo.php");
require_once("security.php");

$o=newObject("data_object",$ID);


if ($o->type=="archive") {
	if (checkReadSecurity($o)) {
		$ID=$o->fileh;
		require("file.php");
	}
	else
	{
		
		header("HTTP/1.0 403 Forbidden");
		
	}
}

else {
	header("HTTP/1.0 403 Forbidden");
	

}




