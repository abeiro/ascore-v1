<?php
$NOCOMPRESS=true;
require_once("Memo.php");
require_once("security.php");

$o=newObject("data_object",$ID);


if ($o->type=="archive") {
	if (checkReadSecurity($o)) {
		header("Location: {$SYS["ROOT"]}Apps/Memo/public_open.php?ID=$ID");
	}
	else
	{
		if ($tmpl=="minilist")
			require("action_fileselector.php");
		else
			require("list.php");
	}
}

else {
	$oldinode=$inode;
	$inode=$o->ID;
	
	if ($tmpl=="minilist")
			require("action_fileselector.php");
		else
			require("list.php");

}




