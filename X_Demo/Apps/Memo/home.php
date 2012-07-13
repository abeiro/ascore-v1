<?php

@define('MEMO_BASE', dirname(__FILE__));
require_once MEMO_BASE . '/lib/base.php';

$title = _("Form List");

$horde_module=MEMO_BASE;
error_reporting(E_ALL ^ E_NOTICE);
require_once("../framework/coreg2.php");

$dir=newObject("data_object");

if (!isset($sort))
	$sort="type DESC";
	
$user=$_SESSION["__auth"]["userId"];
$dir->searchResults=$dir->select("nombre='$user' AND uid='$user'",$offset,$sort);

$match=current($dir->searchResults);

$inode=$match->ID;

require('list.php');



