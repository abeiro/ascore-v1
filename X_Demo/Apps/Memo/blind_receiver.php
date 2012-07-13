<?php

@define('MEMO_BASE', dirname(__FILE__));
require_once MEMO_BASE . '/lib/base.php';

$horde_module=MEMO_BASE;
error_reporting(E_ALL ^ E_NOTICE);
require_once("../framework/coreg2.php");

dataDump($_POST);
dataDump($_FILES);

