<?php

require_once("Memo.php");

//HTML("header_mini");
$o=newObject("data_object",$ID);
plantHTML($o,"view");

$f=newObject("fileh",$o->fileh);
plantHTML($f,"view_fileh");





