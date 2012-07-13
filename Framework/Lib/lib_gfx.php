<?php

/* 

Funcion gfxBoton

Muestra un boton con imágenes de fondo

*/

function gfxBoton($texto,$url,$active=True,$errsms="Privilegios insuficientes") {
if ($active) {
?>

<!--Inicio de código de Botón-->
<table cellspacing="0" border="0" cellpadding="0" style="cursor:pointer;" onClick="location.href='<?php echo $url?>'"><tr>
<td  colspan="0" rowspan="0" align="right"><img src="framework/Data/Images/b_i.gif" border="0"></td>
<td  align="center" valign="center" nowrap style="background-image:url(framework/Data/Images/b_c.gif)">
<span style="color:black;text-decoration:none;" onClick="location.href='<?php echo $url?>'"><?php echo $texto?></span></td>
<td colspan="0" rowspan="0" align="left"><img src="framework/Data/Images/b_d.gif" border="0"></td></tr></table>
<!--Fin de código de Botón-->
 <?php
}
else {
?>
<!--Inicio de código de Botón-->
<table cellspacing="0" border="0" cellpadding="0" style="cursor:pointer;" onClick="alert('<?php echo $errsms?>')"><tr>
<td  style="cursor:pointer;" colspan="0" rowspan="0" align="right"><img src="framework/Data/Images/b_i.gif" border="0"></td>
<td  align="center" valign="center" nowrap style="background-image:url(framework/Data/Images/b_c.gif);">
<span style="color:#AAAAAA;text-decoration:none;" ><?php echo $texto?></span></td>
<td colspan="0" rowspan="0" align="left"><img src="framework/Data/Images/b_d.gif" border="0"></td></tr></table>
<!--Fin de código de Botón-->
<?php
    }
}

/* 

Funcion gfxBotonAction

Muestra un boton con imágenes de fondo

*/

function gfxBotonAction($texto,$action,$active=True,$errsms="Privilegios insuficientes",$tabindex="1",$extra="") {
	if ($active) {
		$data='
		<!--Inicio de código de Botón-->
		<input  type="button" name="actionbutton" '.$extra.' value="'.$texto.'" onclick="'.$action.'" />
		<!--Fin de código de Botón-->
		';
		}
	else {
		$data='
		<!--Inicio de código de Botón-->
				<input type="button" name="actionbutton" readonly value="'.$texto.'" />
		<!--Fin de código de Botón-->
		';
		}
    return $data;
}

function gfxBotonActionStyle($texto,$action,$active=True,$errsms="Privilegios insuficientes",$tabindex="1") {
	if ($active) {
		$data='
		<!--Inicio de código de Botón-->
		<table cellspacing="0" border="0" cellpadding="0" style="cursor:pointer;" onClick="'.$action.'"><tr>
		<td  colspan="0" rowspan="0" align="right"><img src="framework/Data/Images/b_i.gif" border="0"></td>
		<td  align="center" valign="center" nowrap style="background-image:url(framework/Data/Images/b_c.gif)">
		<span  tabindex="'.$tabindex.'" onKeyPress="'.$action.'" style="color:black;text-decoration:none;" >'.$texto.'</span></td>
		<td colspan="0" rowspan="0" align="left"><img src="framework/Data/Images/b_d.gif" border="0"></td></tr></table>
		<!--Fin de código de Botón-->
		';
		}
	else {
		$data='
		<!--Inicio de código de Botón-->
		<table cellspacing="0" border="0" cellpadding="0" style="cursor:pointer;" onClick="alert(\''.$errsms.'\')"><tr>
		<td  style="cursor:pointer;" colspan="0" rowspan="0" align="right"><img src="framework/Data/Images/b_i.gif" border="0"></td>
		<td  align="center" valign="center" nowrap style="background-image:url(framework/Data/Images/b_c.gif);">
		<span style="color:#AAAAAA;text-decoration:none;" >'.$texto.'</span></td>
		<td colspan="0" rowspan="0" align="left"><img src="framework/Data/Images/b_d.gif" border="0"></td></tr></table>
		<!--Fin de código de Botón-->
		';
		}
    return $data;
}

/* 

Funcion gfxBoton

Muestra un boton con imágenes de fondo

*/

function gfxBotonPOST($texto,$url,$active=True,$errsms="Privilegios insuficientes") {
$bid=time();
if ($active) {
?>
<!--Inicio de código de Botón-->
<table cellspacing="0" border="0" cellpadding="0" style="cursor:pointer;" onClick="document.getElementById('<?php echo $bid?>').submit()">
<form action="<?php echo $url?>" method="post" enctype="multipart/form-data" id="<?php echo $bid?>">
<tr>
	<td  colspan="0" rowspan="0" align="right"><img src="framework/Data/Images/b_i.gif" border="0"></td>
	<td align="center" valign="center" nowrap style="background-image:url(framework/Data/Images/b_c.gif);"><span style="color:black;text-decoration:none;"><?php echo $texto?></span></td>
	<td colspan="0" rowspan="0" align="left"><img src="framework/Data/Images/b_d.gif" border="0"></td>
</tr></form></table>
<!--Fin de código de Botón-->
 <?php
}
else {
 ?>
<table cellspacing="0" border="0" cellpadding="0" style="cursor:pointer;" onClick="alert('<?php echo $errsms?>')">
<form action="<?php echo $url?>" method="post" enctype="multipart/form-data" id="<?php echo $bid?>">
<tr>
	<td style="cursor:pointer;" colspan="0" rowspan="0" align="right"><img src="framework/Data/Images/b_i.gif" border="0"></td>
	<td align="center" valign="center" nowrap style="background-image:url(framework/Data/Images/b_c.gif);cursor:pointer;"><a style="color:#AAAAAA;text-decoration:none;" href="#"><?php echo $texto?></a></td>
	<td style="cursor:pointer;"  colspan="0" rowspan="0" align="left"><img src="framework/Data/Images/b_d.gif" border="0"></td>
</tr></form></table>

<?php
	}
}

/* 

Funcion gfxBar

Muestra una barra de porcentaje

*/

function gfxBar($pc,$offcolor="white",$oncolor="#FFA858",$align="center",$size="100%") {
 echo "<table cellpadding=\"0\" cellspacing=\"0\" width=\"$size\" align=\"$align\" style=\"border:1px solid gray;\" border=\"0\">";
 if ($pc>0)
	echo "<tr><td align=\"center\" bgcolor=\"$oncolor\" width=\"".($pc*100)."%\"><span style=\"font-size:6pt\">".floor($pc*100)."%</span></td><td bgcolor=\"$offcolor\">&nbsp;</td>";
else
	echo "<tr></td><td bgcolor=\"$offcolor\"><span style=\"font-size:6pt\">".floor($pc*100)."%</span></td><td bgcolor=\"$offcolor\">&nbsp;</td>";

 echo "<tr>";
 echo "</table>";
}

function gfxBarS($pc,$offcolor="white",$oncolor="#FFA858",$align="center",$size="100%") {
 $r= "<table cellpadding=\"0\" cellspacing=\"0\" width=\"$size\" align=\"$align\" style=\"border:1px solid gray;\" border=\"0\">";
 if ($pc>0)
	$r.="<tr><td align=\"center\" bgcolor=\"$oncolor\" width=\"".($pc*100)."%\"><span style=\"font-size:6pt\">".floor($pc*100)."%</span></td><td bgcolor=\"$offcolor\">&nbsp;   </td>";
else
	$r.= "<tr><td bgcolor=\"$offcolor\"><span style=\"font-size:6pt\">".floor($pc*100)."%</span></td><td bgcolor=\"$offcolor\">   </td>";

 $r.= "</tr>";
 $r.= "</table>";
 return $r;
}
?>
