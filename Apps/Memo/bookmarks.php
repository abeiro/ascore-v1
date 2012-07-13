<?php

require_once("Memo.php");
require_once("security.php");


$dir=newObject("data_object");



$name=str_replace("~",BILO_username(),$name);
$path=explode("/",$name);
$inode=0;
for ($i=0;$i<sizeof($path);$i++) {
	$o=$dir->select("nombre='".$path[$i]."' AND inode='$inode'");
	if ($dir->nRes>0) {
		$res=current($o); 
		$inode=$res->ID;
		//echo $res->nombre;
	}
}


require ('list.php');



