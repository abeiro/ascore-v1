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
if (_query($tmp->sqlGenesis(),True)) {
	_query("SELECT * FROM {$prefix}_{$class}");
	$bdres=_query("SELECT FOUND_ROWS()");
	$aux=_fetch_array($bdres);
	$totalPages=$aux["FOUND_ROWS()"];
	if ($totalPages<1) {
		echo "<pre>Creando elemento inicial</pre>";
		if (_query("INSERT INTO {$prefix}_{$class} (ID) VALUES (1)")) {
			echo "<!--STATUS:OK-->";
			echo "STATUS:OK";
		}
	}

}

?>