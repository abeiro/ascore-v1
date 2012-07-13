<?php

$cClass=ucfirst($class);
$cClass="ps".$cClass;



echo ":: <b>Test CoreG2</b><br>";

$test=new Ente($class);
if (isset($test))
	$status=True;

$test->showProperties();

dataDump($test);

if ($status)
	echo "\n<!--STATUS:OK-->\nSTATUS:OK";
else
	echo "\n<!--STATUS:KO-->\nSTATUS:KO";


