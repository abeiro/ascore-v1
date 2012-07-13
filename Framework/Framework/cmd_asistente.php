<?php

$dir=$SYS["BASE"]."/Apps/".$SYS["PROJECT"]."/local/Class/";
$file=$dir.$class.".def";



if($_POST['option']!=""){


$nombre_campo=strtr(strtolower(trim(strtr($_POST["nombre_campo"]," ","_"))),"����������","aeiouaeiou");


$data1="<{$nombre_campo}  type=\"{$_POST['type']}\"   option=\"{$_POST['option']}\">{$_POST['nombre_etiqueta']}</{$nombre_campo}>";

}else{

$nombre_campo=strtr(strtolower(trim(strtr($_POST["nombre_campo"]," ","_"))),"����������","aeiouaeiou");
$data1="<{$nombre_campo}  type=\"{$_POST['type']}\">{$_POST['nombre_etiqueta']}</{$nombre_campo}>";

}


if (isset($Terminar)) {
	
	
	$datalined=explode("\n",$data);
	foreach ($datalined as $v)
		if(trim($v)!="")
			$newdata.=$v."\n";
	


	$h=fopen($file,"w");
	fwrite($h,stripslashes($newdata));
	fclose($h);
	chmod($file,0775);
	$_SESSION["data2"]="";
	header("Location: {$SYS["ROOT"]}/Backend/{$SYS["PROJECT"]}/dev.php?");
	die();

}



if (isset($Anadir)) {


	
	if($_POST["nombre_etiqueta"]!=""  && $_POST["nombre_campo"]!="" && ($_POST["option"]!="" ||  $_POST["option"]=="" && $_POST["type"]!="string"
        &&  $_POST["type"]!="boolean" && $_POST["type"]!="list" && $_POST["type"]!="longtext" ) ){	

		$data1.="\n";
		$data2 = $_SESSION["data2"].$data1;
		$_SESSION["data2"]=$data2;
	}	
	  
	
}

if (isset($Limpiar)){

$_SESSION["data2"]="";

}




if (file_exists($file))
{
	$buffer=file($file);
	$dat=implode("",$buffer);
	$data=stripslashes($dat);
}



echo '<h3 align="center">'."$class".'</h3>';
$data="<?xml version='1.0' encoding='ISO-8859-15'?>
<cpd>

$data2

</cpd>";


?>


<html>

<head>

<script>

function verifica (){

       tgt=document.getElementById('idType');
       opt=document.getElementById('idOption');
       

       if (tgt.value == "string" || tgt.value == "boolean"|| tgt.value == "list"
        
        || tgt.value == "longtext") {
               opt.style.display='';

	}
	else {
	       opt.style.display='none';
			
	}

	hlp=document.getElementById('idAyuda');
	
 		
        if(tgt.value == "string" || tgt.value == "longtext"){

		hlp.value='Solo numeros enteros';
		
 	}else if(tgt.value == "boolean"){
		hlp.value='Valores Logicos:Si o No';
		

	}else if(tgt.value == "list"){

		hlp.value='Lista de Valores(Ejem:Coche|Casa|Perro|...)';
		
	}
 
           

}
function alerta()
{


	if (document.getElementById('nc').value=="" || document.getElementById('ne').value==""|| (document.getElementById('op').value=="" && document.getElementById('idOption').style.display!='none'      )){
	
		
		window.alert("No puede dejar campos vac�os");
		

	}



}



function mySubmit(myURL) {
      
	frm=document.getElementById('idForm');
	frm.action=myURL;
	frm.submit();

}


</script>



</head>

<form  method="POST" enctype="multipart/form-data" id="idForm">

<fieldset>
<legend>Formulario de creacion</legend>
<table align="center">

<tr>

<td>Nombre del Campo: </td>
<td><input  align="middle" type="text" size="27" id="nc" name="nombre_campo" > </td>
<td>(Nombre que llevaria el campo de la tabla en la base de datos,no utilice espacios en blanco ni caracteres especiales)</td>
</tr>

<tr>

<td>Nombre de la etiqueta:</td>
<td><input align="right" type="text" size="27" id="ne" name="nombre_etiqueta" ></td>
<td>(Nombre que se visualizara en el formulario)</td>

</tr>

<tr>

<td>Tipo de dato: </td>
<td>
<select name="type" onchange="verifica();" id="idType">

	<OPTION value="string">Cadena</OPTION>
	<OPTION value="date">Fecha</OPTION>
	<OPTION value="time" >Hora</OPTION>
	<OPTION value="int">Numero Entero</OPTION>
	<OPTION value="list">Lista de Valores</OPTION>
	<OPTION value="ref">Numero de Referencia (ID)</OPTION>
	<OPTION value="money">Valor Monetario</OPTION>
	<OPTION value="float">Numero Real</OPTION>
	<OPTION value="boolean">Valor Logico</OPTION>
	<OPTION value="longtext">Cadena Larga</OPTION>

 </select>
</td>
<td>(Tipo de dato del campo)</td>

</tr>

<tr align="left" id="idOption" >

<td> Opciones:</td>
<td><input type="text" size="27" id="op" name="option" ></td>
<td >

<textarea rows="1" cols="50" id="idAyuda" readonly="true"  style="background-color:none; border:0px" >Solo numeros enteros</textarea>

</td>

</textarea>


<tr align="center"><td>
<input    align="middle"  onclick="mySubmit('<?php echo "{$SYS["ROOT"]}/Backend/{$SYS["PROJECT"]}/dev.php?command=asistente"?>');alerta()" type="submit" name="Anadir" value="A�adir" >
</td>
<td><input  onclick="mySubmit('<?php echo "{$SYS["ROOT"]}/Backend/{$SYS["PROJECT"]}/dev.php?command=asistente"?>')" align="middle"  type="submit" name="Limpiar" value="Limpiar" >
</td>

</tr>


</table>
</fieldset>
<br>

<div align="center"><textarea name="data" cols="80" rows="25">
<?php echo $data?>
</textarea>  </div>
<input type="hidden" name="class" value="<?php echo $class?>">

<div align="center"> 



&nbsp;

<input  onclick="mySubmit('<?php echo "{$SYS["ROOT"]}/Backend/{$SYS["PROJECT"]}/dev.php?command=asistente"?>')" align="middle"  type="submit" name="Terminar" value="Terminar" >





</div>

</form>
<br>
 <div align="center">[ <a href="?" >Volver</a>]</div>
<html>