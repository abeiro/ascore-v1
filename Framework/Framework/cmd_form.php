<?php
while(@ob_end_clean());
header("Content-Type: text/plain");
set_include_dir("Framework/Extensions/xajax/-");
require_once 'xajax_core/xajax.inc.php';
require_once("Extensions/wGui/wUI.php");
require_once("Extensions/wGui/wUtilities.php");

$xajax = new xajax();

$cClass=newObject($class);
$FormWindow = new wSimplePane("RegistroPane");
$f=new wForm("FORM_NAME",$FormWindow);
$f->setDataModelFromCore($cClass);

die($f->renderAsPseudoCode());