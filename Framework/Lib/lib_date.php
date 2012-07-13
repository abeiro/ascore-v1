<?php

function as_libdate_fecha($stamp="") {
	if (empty($stamp))
		$stamp=time();
	$dia=ucfirst(strftime("%A",$stamp));
	$dia_n=strftime("%d",$stamp)+0;
	$mes=ucfirst(strftime("%B",$stamp));
	$anio=strftime("%Y",$stamp);
	return "$dia, $dia_n de $mes de $anio";
}

function dateFecha1($stamp="") {
    return as_libdate_fecha($stamp);
}

function dateFecha2($stamp="") {
	if (empty($stamp))
		$stamp=time();
	return strftime("%d/%m/%Y",$stamp);

}


//Mejorar
function validate_fecha($d) {
	$fecha=explode("/",$d);
	if(!isset($fecha[2]) OR empty($fecha[2])) return false;
	else return true;

}

function int_to_text($fecha){
	return strftime("%d/%m/%Y",$fecha);
}

function text_to_int($time,$wa=true){
	$fecha=explode("/", $time);
	$fecha=strtotime($fecha[1]."/".$fecha[0]."/".$fecha[2]);
	if ($wa)
		$fecha+=3600;
	return $fecha;
}
function int_to_text_ex($fecha){
	$datex="$fecha";
	$y=substr($datex,0,4);
	$m=substr($datex,4,2);
	$d=substr($datex,6,2);
	return "$d/$m/$y";
}

function text_to_int_ext($time,$wa=true){
	$fecha=explode("/", $time);
	$fecha=$fecha[0]+$fecha[1]*100+$fecha[2]*10000;
	
	return $fecha;
}

/*
dateTodayStamp()
Devuelve el timestamp del día actual a las 00:00:00
*/
function dateTodayStamp($stamp="") {
	if (empty($stamp))
		$stamp=time();
	return mktime(0,0,1,date("m",$stamp),date("d",$stamp),date("Y",$stamp));
}

function yesterday() {
	$stamp=dateTodayStamp()-1;
	return dateTodayStamp($stamp);
	
}

function hour_to_int($data) {
	
	//echo "<b>### $data</b>";
	$arr=explode(":",strtr($data,"'",""));
	return ($arr[0]*60+$arr[1]);
	
	
}

/* Funciones de fecha. Dan el timestamp tal y como se guarda en la BBDD


Hoy            -> asTimeStamp();
Ayer           -> asTimeStampYesterday();
Hace dos dias  -> asTimeStampYesterday(asTimeStampYesterday());
Hace tres dias -> asTimeStampYesterday(asTimeStampYesterday(asTimeStampYesterday()));
El dias antes 
de 01/01/1980  -> asTimeStampYesterday(text_to_int("01/01/1980"));

*/

function asTimeStamp($stamp='') {
	
	if (empty($stamp))
		$stamp=time();
	
	return dateTodayStamp($stamp);
	
	
}

function asTimeStampYesterday($stamp='') {
	
	if (empty($stamp))
		$stamp=time();
	return dateTodayStamp(yesterday()-2);
	
	
	
}
?>
