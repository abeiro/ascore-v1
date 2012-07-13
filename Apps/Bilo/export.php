<?php

require_once("Bilo.php");

header("Content-Type: plain/text");
header("Content-Disposition: attachment; filename=registros.csv");

$r=newObject("registro");
if (empty($sort))
	$sort=" dia DESC ";
setlimitRows(5000);
$r->searchResults=$r->selectAll($offset,$sort);
echo "username,nombre,apellidos,dia,entrada,salida\n";

do {
	$o=current($r->searchResults);
	echo $o->get_ext("user",$o->user_id,"username").",";
	echo $o->get_ext("user",$o->user_id,"nombre").",";
	echo $o->get_ext("user",$o->user_id,"apellidos").",";
	echo int_to_text($o->dia).",";
	$void=($o->entrada_m>3600) ?  strftime("%H:%M",$o->entrada_m).",":  "-,";
	echo $void;
	$void=($o->salida_m>3600) ?  strftime("%H:%M",$o->salida_m):  "-";
	echo $void;
	echo "\n";
	
} while (next($r->searchResults));
resetlimitRows();

//require $registry->get('templates', 'horde') . '/common-footer.inc';












