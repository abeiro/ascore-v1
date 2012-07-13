<?php


$dir=$SYS["BASE"]."/Apps/".$SYS["PROJECT"]."/local/Class/";
$file=$dir.$class.".def";


$buffer=file($file);
$dat=implode("",$buffer);
$data=stripslashes($dat);


$objeto=newObject($class);
$control=0;

if (isset($actu)){


foreach ($_POST['nombre_campo'] as $i=>$v){
	



	if($v==""  || $_POST['nombre_etiqueta']==""){
		$_SESSION["data2"]="";
		$control=1;
		break;	
	
	

	}
	else if($_POST['nombre_etiqueta'][$i]!=""  && $v!="" ){	
		$control=0;
		
	

		$v=strtr(strtolower(trim(strtr($_POST["nombre_campo"][$i]," ","_"))),"·ÈÌÛ˙¡…Õ”⁄","aeiouaeiou");
	


		if ($_POST['borrar'][$i]==true){
			$data1="";		
		}else if($_POST['opciones'][$i]!="" && ($_POST['type'][$i]!="string" && $_POST['type'][$i]!="boolean" && $_POST['type'][$i]!="list" && $_POST['type'][$i]!="longtext")){
	
			$data1="<{$v}  type=\"{$_POST['type'][$i]}\">{$_POST['nombre_etiqueta'][$i]}</{$v}>";
			$opciones[$i]="";
	
		
		
		}else if($_POST['opciones'][$i]!=""){	
		
			$data1="<{$v} type=\"{$_POST['type'][$i]}\"   option=\"{$_POST['opciones'][$i]}\">{$_POST['nombre_etiqueta'][$i]}</{$v}>";
		}else{
			
			$data1="<{$v}  type=\"{$_POST['type'][$i]}\">{$_POST['nombre_etiqueta'][$i]}</{$v}>";



		}
	
		$data1.="\n";
		$data2 = $_SESSION["data2"].$data1;
		$_SESSION["data2"]=$data2;
	}

}

if($control==1){
	header("Location: {$SYS["ROOT"]}/Backend/{$SYS["PROJECT"]}/dev.php?command=edit_class&class=$class");
	die();


}else{

$data="<?xml version='1.0' encoding='ISO-8859-15'?>
<cpd>
$data2</cpd>";
	
$_SESSION["data2"]="";

	


	
	$h=fopen($file,"w");
	fwrite($h,stripslashes($data));
	fclose($h);
	chmod($file,0775);
	header("Location: {$SYS["ROOT"]}/Backend/{$SYS["PROJECT"]}/dev.php?command=edit_class&class=$class");
	die();
	
}

}
if(isset($Terminar)){
header("Location: {$SYS["ROOT"]}/Backend/{$SYS["PROJECT"]}/dev.php?");
	die();


}



foreach ($objeto->properties_desc as $i=>$v){


	if($i=="S_UserID_CB"){
	break;
	}else{
	$Nombre_camp[]=$i;
	$Nombre_Etiquet[]=$v;
	}
}


foreach ($objeto->properties_type as $i=>$v){


	if($i=="S_UserID_CB"){
	break;
	}else{
	$t=explode(":",$v);
	$tipos[]=$t[0];
	$opciones[]=$t[1];
	}
}






$e=0;
$iter=count($Nombre_camp);
if (isset($a√±adir)){
	$iter++;

}
echo "

<html>
<head>

<script>
function validarSiNumero(numero){

	if (!/^([0-9])*$/.test(numero))
		alert('El valor ' + numero + ' no es un numero');

}
function verifica ()
{
       tgt=document.getElementById('idType');
       opt=document.getElementById('idOption');

	if (tgt.value == 'string' || tgt.value == 'boolean'|| tgt.value == 'list'
        
        || tgt.value == 'longtext') {
               opt.style.display='';

	}
	else {
		opt.style.display='';
	        opt.value='';
	}

	hlp=document.getElementById('idAyuda');
	
 		
        if(tgt.value == 'string' || tgt.value == 'longtext'){

		hlp.value='Solo numeros enteros';
		
 	}else if(tgt.value == 'boolean'){
		hlp.value='Valores Logicos:Si o No';
		

	}else if(tgt.value == 'list'){

		hlp.value='Lista de Valores(Ejem:Coche|Casa|Perro|...)';
		
	}else{hlp.value='No escriba nada en opciones';}
 
           

}

function mySubmit(myURL) {
      
	frm=document.getElementById('idForm');
	frm.action=myURL;
	frm.submit();

}


</script>

</head>

<form  method='POST' enctype='multipart/form-data'>
<fieldset width=50>
<legend align='center' style='color:black;font-size:18pt'>".$class."</legend>
";

while ($e<$iter){

echo "

<table align='center'>
";if($e==0){echo "
<tr>

<th>Nombre Campo</th>
<th>Nombre Etiqueta</th>
<th>Tipo de Dato</th>
<th>Opciones</th>
</tr>";
}echo "
<tr>
<td>
<input type='text' id='nc' name='nombre_campo[]' value='".$Nombre_camp[$e]."'>

</td>

<td>

<input type='text' id='ne' name='nombre_etiqueta[]' value='".$Nombre_Etiquet[$e]."'>

</td>
<td>

"; if ($tipos[$e] == "string"){echo "

<select name='type[]' onchange='verifica()' id='idType' >
	<OPTION selected id='st' value='string'>Cadena</OPTION>
	<OPTION id='da' value='date'>Fecha</OPTION>
	<OPTION id='ti' value='time' >Hora</OPTION>
	<OPTION id='in' value='int'>Numero Entero</OPTION>
	<OPTION id='li' value='list'>Lista de Valores</OPTION>
	<OPTION id='re' value='ref'>Numero de Referencia (ID)</OPTION>
	<OPTION id='mo' value='money'>Valor Monetario</OPTION>
	<OPTION id='fl' value='float'>Numero Real</OPTION>
	<OPTION id='bo' value='boolean'>Valor Logico</OPTION>
	<OPTION id='lo' value='longtext'>Cadena Larga</OPTION>
</select>
";
}else if ($tipos[$e] == "date"){echo "
<select name='type[]' onchange='verifica()' id='idType' >
	<OPTION id='st' value='string'>Cadena</OPTION>
	<OPTION selected id='da' value='date'>Fecha</OPTION>
	<OPTION id='ti' value='time' >Hora</OPTION>
	<OPTION id='in' value='int'>Numero Entero</OPTION>
	<OPTION id='li' value='list'>Lista de Valores</OPTION>
	<OPTION id='re' value='ref'>Numero de Referencia (ID)</OPTION>
	<OPTION id='mo' value='money'>Valor Monetario</OPTION>
	<OPTION id='fl' value='float'>Numero Real</OPTION>
	<OPTION id='bo' value='boolean'>Valor Logico</OPTION>
	<OPTION id='lo' value='longtext'>Cadena Larga</OPTION>
</select>
";
}else if ($tipos[$e] == "int"){echo "
<select name='type[]' onchange='verifica()' id='idType' >
	<OPTION id='st' value='string'>Cadena</OPTION>
	<OPTION id='da' value='date'>Fecha</OPTION>
	<OPTION id='ti' value='time' >Hora</OPTION>
	<OPTION selected id='in' value='int'>Numero Entero</OPTION>
	<OPTION id='li' value='list'>Lista de Valores</OPTION>
	<OPTION id='re' value='ref'>Numero de Referencia (ID)</OPTION>
	<OPTION id='mo' value='money'>Valor Monetario</OPTION>
	<OPTION id='fl' value='float'>Numero Real</OPTION>
	<OPTION id='bo' value='boolean'>Valor Logico</OPTION>
	<OPTION id='lo' value='longtext'>Cadena Larga</OPTION>
</select>
";
}else if ($tipos[$e] == "time"){echo "
<select name='type[]' onchange='verifica()' id='idType' >
	<OPTION id='st' value='string'>Cadena</OPTION>
	<OPTION id='da' value='date'>Fecha</OPTION>
	<OPTION selected id='ti' value='time' >Hora</OPTION>
	<OPTION id='in' value='int'>Numero Entero</OPTION>
	<OPTION id='li' value='list'>Lista de Valores</OPTION>
	<OPTION id='re' value='ref'>Numero de Referencia (ID)</OPTION>
	<OPTION id='mo' value='money'>Valor Monetario</OPTION>
	<OPTION id='fl' value='float'>Numero Real</OPTION>
	<OPTION id='bo' value='boolean'>Valor Logico</OPTION>
	<OPTION id='lo' value='longtext'>Cadena Larga</OPTION>
</select>
";
}else if ($tipos[$e] == "list"){echo "
<select name='type[]' onchange='verifica()' id='idType' >
	<OPTION id='st' value='string'>Cadena</OPTION>
	<OPTION id='da' value='date'>Fecha</OPTION>
	<OPTION id='ti' value='time' >Hora</OPTION>
	<OPTION id='in' value='int'>Numero Entero</OPTION>
	<OPTION selected id='li' value='list'>Lista de Valores</OPTION>
	<OPTION id='re' value='ref'>Numero de Referencia (ID)</OPTION>
	<OPTION id='mo' value='money'>Valor Monetario</OPTION>
	<OPTION id='fl' value='float'>Numero Real</OPTION>
	<OPTION id='bo' value='boolean'>Valor Logico</OPTION>
	<OPTION id='lo' value='longtext'>Cadena Larga</OPTION>
</select>
";
}else if ($tipos[$e] == "ref"){echo "
<select name='type[]' onchange='verifica()' id='idType' >
	<OPTION id='st' value='string'>Cadena</OPTION>
	<OPTION id='da' value='date'>Fecha</OPTION>
	<OPTION id='ti' value='time' >Hora</OPTION>
	<OPTION id='in' value='int'>Numero Entero</OPTION>
	<OPTION id='li' value='list'>Lista de Valores</OPTION>
	<OPTION selected id='re' value='ref'>Numero de Referencia (ID)</OPTION>
	<OPTION id='mo' value='money'>Valor Monetario</OPTION>
	<OPTION id='fl' value='float'>Numero Real</OPTION>
	<OPTION id='bo' value='boolean'>Valor Logico</OPTION>
	<OPTION id='lo' value='longtext'>Cadena Larga</OPTION>
</select>
";
}else if ($tipos[$e] == "money"){echo "
<select name='type[]' onchange='verifica()' id='idType' >
	<OPTION id='st' value='string'>Cadena</OPTION>
	<OPTION id='da' value='date'>Fecha</OPTION>
	<OPTION id='ti' value='time' >Hora</OPTION>
	<OPTION id='in' value='int'>Numero Entero</OPTION>
	<OPTION id='li' value='list'>Lista de Valores</OPTION>
	<OPTION id='re' value='ref'>Numero de Referencia (ID)</OPTION>
	<OPTION selected id='mo' value='money'>Valor Monetario</OPTION>
	<OPTION id='fl' value='float'>Numero Real</OPTION>
	<OPTION id='bo' value='boolean'>Valor Logico</OPTION>
	<OPTION id='lo' value='longtext'>Cadena Larga</OPTION>
</select>
";
}else if ($tipos[$e] == "float"){echo "
<select name='type[]' onchange='verifica()' id='idType' >
	<OPTION id='st' value='string'>Cadena</OPTION>
	<OPTION id='da' value='date'>Fecha</OPTION>
	<OPTION id='ti' value='time' >Hora</OPTION>
	<OPTION id='in' value='int'>Numero Entero</OPTION>
	<OPTION id='li' value='list'>Lista de Valores</OPTION>
	<OPTION id='re' value='ref'>Numero de Referencia (ID)</OPTION>
	<OPTION id='mo' value='money'>Valor Monetario</OPTION>
	<OPTION selected id='fl' value='float'>Numero Real</OPTION>
	<OPTION id='bo' value='boolean'>Valor Logico</OPTION>
	<OPTION id='lo' value='longtext'>Cadena Larga</OPTION>
</select>

";
}else if ($tipos[$e] == "boolean"){echo "
<select name='type[]' onchange='verifica()' id='idType' >
	<OPTION id='st' value='string'>Cadena</OPTION>
	<OPTION id='da' value='date'>Fecha</OPTION>
	<OPTION id='ti' value='time' >Hora</OPTION>
	<OPTION id='in' value='int'>Numero Entero</OPTION>
	<OPTION id='li' value='list'>Lista de Valores</OPTION>
	<OPTION id='re' value='ref'>Numero de Referencia (ID)</OPTION>
	<OPTION id='mo' value='money'>Valor Monetario</OPTION>
	<OPTION id='fl' value='float'>Numero Real</OPTION>
	<OPTION selected id='bo' value='boolean'>Valor Logico</OPTION>
	<OPTION id='lo' value='longtext'>Cadena Larga</OPTION>
</select>
";
}else if ($tipos[$e] == "longtext"){echo "
<select name='type[]' onchange='verifica()' id='idType' >
	<OPTION id='st' value='string'>Cadena</OPTION>
	<OPTION id='da' value='date'>Fecha</OPTION>
	<OPTION id='ti' value='time' >Hora</OPTION>
	<OPTION id='in' value='int'>Numero Entero</OPTION>
	<OPTION id='li' value='list'>Lista de Valores</OPTION>
	<OPTION id='re' value='ref'>Numero de Referencia (ID)</OPTION>
	<OPTION id='mo' value='money'>Valor Monetario</OPTION>
	<OPTION id='fl' value='float'>Numero Real</OPTION>
	<OPTION id='bo' value='boolean'>Valor Logico</OPTION>
	<OPTION selected id='lo' value='longtext'>Cadena Larga</OPTION>
</select>

";}else{echo "
<select name='type[]' onchange='verifica()' id='idType' >
	<OPTION selected id='st' value='string'>Cadena</OPTION>
	<OPTION id='da' value='date'>Fecha</OPTION>
	<OPTION id='ti' value='time' >Hora</OPTION>
	<OPTION id='in' value='int'>Numero Entero</OPTION>
	<OPTION id='li' value='list'>Lista de Valores</OPTION>
	<OPTION id='re' value='ref'>Numero de Referencia (ID)</OPTION>
	<OPTION id='mo' value='money'>Valor Monetario</OPTION>
	<OPTION id='fl' value='float'>Numero Real</OPTION>
	<OPTION id='bo' value='boolean'>Valor Logico</OPTION>
	<OPTION id='lo' value='longtext'>Cadena Larga</OPTION>
</select>

";}echo "
</td>

<td>
<input type='text' name='opciones[]' value='".$opciones[$e]."' id='idOption' >
</td>

<td>
<INPUT type='checkbox' name='borrar[]' value='Borrar'><u>BORRAR</u>
</td>
</tr></table>
";$e++;}

echo "
<br>
<div align='center'><INPUT type='submit' name='actu' value='Actualizar'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<INPUT type='submit' name='a√±adir' value='Nuevo Campo''></div>
</fieldset>
<br><br>

<div align='center'><textarea name='data' cols='80' rows='25'>
".$data."
</textarea>  </div>

<input type='hidden' name='class' value='".$class."'>

<div align='center'><br>
<input 
onclick='mySubmit(\"{$SYS["ROOT"]}/Backend/{$SYS["PROJECT"]}/dev.php?command=edit_class\")' type='submit' name='Terminar' value='Terminar'></div>
</form>
<br>

<div align='center'>[ <a href='?'>Volver</a>]</div>
</html>";



?>