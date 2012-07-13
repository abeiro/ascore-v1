<?php

require_once("Memo.php");
require_once("security.php");

$form=newObject("data_object",$inode);

$inode=$form->inode;
require 'list.php';


?>