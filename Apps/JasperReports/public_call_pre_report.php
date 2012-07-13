<?php

require_once("JasperReports.php");

echo "
<script>

ROOT='<!-- D:ROOT -->';
PROJECT='<!-- D:PROJECT -->';

fecha_inicio=prompt('Introduzca la fecha inicio','dd/mm/aaaa');
fecha_fin=prompt('Introduzca la fecha fin','dd/mm/aaaa');

if(fecha_inicio != null && fecha_fin != null)
	location.href='{$SYS["ROOT"]}Apps/{$SYS["PROJECT"]}/public_call_report.php?informe={$_GET["informe"]}&fecha_inicio='+fecha_inicio+'&fecha_fin='+fecha_fin;

</script>
";

?>