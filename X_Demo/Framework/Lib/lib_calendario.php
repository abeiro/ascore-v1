<?
function calcula_numero_dia_semana($dia,$mes,$ano){
	$numerodiasemana = date('w', mktime(0,0,0,$mes,$dia,$ano));
	if ($numerodiasemana == 0) 
		$numerodiasemana = 6;
	else
		$numerodiasemana--;
	return $numerodiasemana;
}

//funcion que devuelve el último día de un mes y año dados
function ultimoDia($mes,$ano){ 
    $ultimo_dia=28;
    while (checkdate($mes,$ultimo_dia + 1,$ano)){
       $ultimo_dia++;
    } 
    return $ultimo_dia; 
} 

function dame_nombre_mes($mes){
	 switch ($mes){
	 	case 1:
			$nombre_mes="Enero";
			break;
	 	case 2:
			$nombre_mes="Febrero";
			break;
	 	case 3:
			$nombre_mes="Marzo";
			break;
	 	case 4:
			$nombre_mes="Abril";
			break;
	 	case 5:
			$nombre_mes="Mayo";
			break;
	 	case 6:
			$nombre_mes="Junio";
			break;
	 	case 7:
			$nombre_mes="Julio";
			break;
	 	case 8:
			$nombre_mes="Agosto";
			break;
	 	case 9:
			$nombre_mes="Septiembre";
			break;
	 	case 10:
			$nombre_mes="Octubre";
			break;
	 	case 11:
			$nombre_mes="Noviembre";
			break;
	 	case 12:
			$nombre_mes="Diciembre";
			break;
	}
	return $nombre_mes;
}

function dame_estilo($dia_imprimir){
	global $mes,$ano,$dia_solo_hoy,$tiempo_actual;
	//dependiendo si el día es Hoy, Domigo o Cualquier otro, devuelvo un estilo
	if ($dia_solo_hoy == $dia_imprimir && $mes==date("n", $tiempo_actual) && $ano==date("Y", $tiempo_actual)){
		//si es hoy
		$estilo = " class='hoy'";
	}else{
		$fecha=mktime(12,0,0,$mes,$dia_imprimir,$ano);
		if (date("w",$fecha)==0){
			//si es domingo 
			$estilo = " class='domingo'";
		}else{
			//si es cualquier dia
			$estilo = " class='diario'";
		}
	}
	return $estilo;
}
function mostrar_calendario($mes,$ano,$parametros_formulario){
	
	//tomo el nombre del mes que hay que imprimir
	$nombre_mes = dame_nombre_mes($mes);
	
	//construyo la cabecera de la tabla
	echo "<table style=\"position:absolute;right:0px;top:45px;border:1px solid gray\" width=200 bgcolor=\"#FBEFC7\" cellspacing=3 cellpadding=2 border=0 align=\"right\">";
	echo "<tr><td align=center><span style=\"cursor:pointer\" onclick=\"getElementById('tabla_calendar').style.display=(getElementById('tabla_calendar').style.display=='none') ? '' : 'none'; \">Calendario</span></td></tr>";
	echo "<tr><td >";
	echo "<table  width=\"100%\" bgcolor=\"#F1C021\" cellspacing=\"2\" cellpadding=\"2\" border=\"1\"><tr><td>";
	//calculo el mes y ano del mes anterior
	$mes_anterior = $mes - 1;
	$ano_anterior = $ano;
	if ($mes_anterior==0){
		$ano_anterior--;
		$mes_anterior=12;
	}
	echo "<a  href=?$parametros_formulario&mes=$mes_anterior&ano=$ano_anterior>&lt;&lt;</a></td>";
	   echo "<td align=center style=\"font-size:11px;color: #000000;margin:3px;font-weight:bold;\">$nombre_mes $ano</td>";
	   echo "<td align=right>";
	//calculo el mes y ano del mes siguiente
	$mes_siguiente = $mes + 1;
	$ano_siguiente = $ano;
	if ($mes_siguiente==13){
		$ano_siguiente++;
		$mes_siguiente=1;
	}
	echo "<a href=?$parametros_formulario&mes=$mes_siguiente&ano=$ano_siguiente>&gt;&gt;</a></td></tr></table></td></tr>";
	echo '	<tr><td id="tabla_calendar" style="display:none"><table width="100%"><tr>
			    <td width=14% align=center class=altn>L</td>
			    <td width=14% align=center class=altn>M</td>
			    <td width=14% align=center class=altn>X</td>
			    <td width=14% align=center class=altn>J</td>
			    <td width=14% align=center class=altn>V</td>
			    <td width=14% align=center class=altn>S</td>
			    <td width=14% align=center class=altn>D</td>
			</tr>';
	
	//Variable para llevar la cuenta del dia actual
	$dia_actual = 1;

	//calculo el numero del dia de la semana del primer dia
	$numero_dia = calcula_numero_dia_semana(1,$mes,$ano);
	//echo "Numero del dia de demana del primer: $numero_dia <br>";
	
	//calculo el último dia del mes
	$ultimo_dia = ultimoDia($mes,$ano);
	
	//escribo la primera fila de la semana
	echo "<tr>";
	for ($i=0;$i<7;$i++){
		if ($i < $numero_dia){
			//si el dia de la semana i es menor que el numero del primer dia de la semana no pongo nada en la celda
			echo "<td></td>";
		} else {
			echo "<td align=center><a href=\"?$parametros_formulario&day2analize=".mktime(0,0,1,$mes,$dia_actual,$ano)."\">$dia_actual</a></td>";
			$dia_actual++;
		}
	}
	echo "</tr>";
	
	//recorro todos los demás días hasta el final del mes
	$numero_dia = 0;
	while ($dia_actual <= $ultimo_dia){
		//si estamos a principio de la semana escribo el <TR>
		if ($numero_dia == 0)
			echo "<tr>";
		echo "<td align=center><a href=\"?$parametros_formulario&day2analize=".mktime(0,0,1,$mes,$dia_actual,$ano)."\">$dia_actual</a></td>";
		$dia_actual++;
		$numero_dia++;
		//si es el uñtimo de la semana, me pongo al principio de la semana y escribo el </tr>
		if ($numero_dia == 7){
			$numero_dia = 0;
			echo "</tr>";
		}
	}

	//compruebo que celdas me faltan por escribir vacias de la última semana del mes
	for ($i=$numero_dia;$i<7;$i++){
		echo "<td></td>";
	}

	echo "</tr>";
	echo "</table></td></tr></table>";
	echo "</tr></td></table>";
}

function formularioCalendario($mes,$ano){
	global $parametros_formulario;
echo '
	<table align="center" cellspacing="2" cellpadding="2" border="0" class=tform>
	<tr><form name="theform" action="?' . $parametros_formulario . '&raise=true" method="POST">';
echo '
    <td align="center" valign="top">
		<select name=nuevo_mes onchange=document.theform.submit();>
		<option value="1"';
if ($mes==1)
 echo "selected";
echo'>Enero
		<option value="2" ';
if ($mes==2)
	echo "selected";
echo'>Febrero
		<option value="3" ';
if ($mes==3)
	echo "selected";
echo'>Marzo
		<option value="4" ';
if ($mes==4)
	echo "selected";
echo '>Abril
		<option value="5" ';
if ($mes==5)
		echo "selected";
echo '>Mayo
		<option value="6" ';
if ($mes==6)
	echo "selected";
echo '>Junio
		<option value="7" ';
if ($mes==7)
	echo "selected";
echo '>Julio
		<option value="8" ';
if ($mes==8)
	echo "selected";
echo '>Agosto
		<option value="9" ';
if ($mes==9)
	echo "selected";
echo '>Septiembre
		<option value="10" ';
if ($mes==10)
	echo "selected";
echo '>Octubre
		<option value="11" ';
if ($mes==11)
	echo "selected";
echo '>Noviembre
		<option value="12" ';
if ($mes==12)
    echo "selected";
echo '>Diciembre
		</select>
		</td>';
echo '
	    <td align="center" valign="top">
		<select name=nuevo_ano onchange=document.theform.submit();>';

for ($cont=1900;$cont<$ano+3;$cont++){
	echo "<option value='$cont'";
	if ($ano==$cont)
   		echo " selected";
   	echo ">$cont";
}
echo '
	</select>
		</td>';
echo '
	</tr>
	</table>
	</form>';
}


//calendario
function as_libcal_creaInputCal($nombrecampo,$nombreformulario,$default=""){
	global $raiz;
	echo '<INPUT name="'.$nombrecampo.'" size="11" value="'.$default.'">';
    echo '<span style="cursor:help;" ';
	echo 'onclick="muestraCalendario(\''. $raiz.'\',\''. $nombreformulario .'\',\''.$nombrecampo.'\')">';
	echo "[Calendario]</span>";
}


if ($raise=="true") {
?>
    <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
  <title>Calendario.</title>
  <meta name="GENERATOR" content="Quanta Plus">

<style type="text/css">
body,td,select,a,input{
	font-family:sans-serif;
	font-size:9px;
	text-decoration:none;
}

.domingo{
	color:red;
	text-decoration:none;
}
.diario {
	color:black;
	text-decoration:none;
}
.hoy {
	color:blue;
	text-decoration:none;
}
.altn{
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size:9px;
	color: #000000;
	font-weight:bold;
}
a{
	font-family: Verdana, Arial, Helvetica, sans-serif;
	color: #000000;
	text-decoration:none;
}

</style>
</head>
<body bgcolor="#FBEFC7" >

	<script>
		function devuelveFecha(dia,mes,ano){
			//Se encarga de escribir en el formulario adecuado los valores seleccionados
			//también debe cerrar la ventana del calendario
			var formulario_destino = '<?echo $_GET["formulario"]?>'

			var campo_destino = '<?echo $_GET["nomcampo"]?>'

			//meto el dia
			eval ("opener.document." + formulario_destino + "." + campo_destino + ".value='" + dia + "/" + mes + "/" + ano + "'")
			window.close()
		}
	</script>

<?
//TOMO LOS DATOS QUE RECIBO POR LA url Y LOS COMPONGO PARA PASARLOS EN SUCESIVAS EJECUCIONES DEL CALENDARIO
$parametros_formulario = "formulario=" . $_GET["formulario"] . "&nomcampo=" . $_GET["nomcampo"];
?>

<div align="center">
<?
$tiempo_actual = time();
$dia_solo_hoy = date("d",$tiempo_actual);
if (!$_POST && !isset($_GET["nuevo_mes"]) && !isset($_GET["nuevo_ano"])){
	$mes = date("n", $tiempo_actual);
	$ano = date("Y", $tiempo_actual);
}elseif ($_POST) {
	$mes = $_POST["nuevo_mes"];
	$ano = $_POST["nuevo_ano"];
}else{
	$mes = $_GET["nuevo_mes"];
	$ano = $_GET["nuevo_ano"];
}

mostrar_calendario($mes,$ano);
formularioCalendario($mes,$ano);
?>
</div>
</body>
</html>

<?
}
else {
?>



<script language="JavaScript">
var ventanaCalendario=false

function muestraCalendario(raiz,formulario_destino,campo_destino,mes_destino,ano_destino){
	//funcion para abrir una ventana con un calendario.
	//Se deben indicar los datos del formulario y campos que se desean editar con el calendario, es decir, los campos donde va la fecha.
	if (typeof ventanaCalendario.document == "object") {
		ventanaCalendario.close()
	}
	ventanaCalendario = window.open("<?php echo _ROOT?>/../Lib/lib_calendario.php?formulario=" + formulario_destino + "&nomcampo=" + campo_destino+"&raise=true",campo_destino,"width=240,height=250,left=100,top=100,scrollbars=no,menubars=no,statusbar=NO,status=NO,resizable=NO,location=NO")
}
</script>
<?
}
?>
