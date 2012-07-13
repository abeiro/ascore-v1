<?php
$core= new core();
echo "<hr><div style=\"font-size:12pt;font-weight:bold;\" align=\"center\">Core G2. version ".$core->VERSION."</div><br><hr>";

if (empty($class))
	$class="example";
$tmp=new Ente($class);



$module=$SYS["PROJECT"];
$column="ID";

echo "<pre>Nombre de Clase: ".$class."\n";
echo "Nombre de Modulo: ".$SYS["PROJECT"]."\n";
echo "Nombre de Tabla. {$prefix}_{$class}\n";
echo "Clave primaria. ID"."\n</pre>";

echo "<pre>Generando Tabla</pre>";
if (_query("ALTER TABLE `{$prefix}_{$class}` TYPE = innodb")) {
	echo "<!--STATUS:OK-->";
	echo "STATUS:OK";

}
$res=_query("SHOW TABLE STATUS LIKE '{$prefix}_{$class}'");
dataDump(_fetch_array($res));



?>

