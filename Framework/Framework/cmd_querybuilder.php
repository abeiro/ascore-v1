<?php
if ($action=="delete") {
	plantHTML(Array(),"action_header");
	$d=newObject("queryb",$ID);
	if ($d->delete())
		echo "borrado";
	else
		echo "no borrado";
	plantHTML(Array(),"action_footer");
	frameReload("fbody");
	die();

}
else if ($action=="edit") {
	
	$d=newObject("queryb",$ID);
	plantHTML($d,"query_edit");
	die();

}
else if ($action=="save") {
	plantHTML(Array(),"action_header");
	$d=newObject("queryb",$ID);
	$d->setAll($_POST);
		if ($d->save())
		echo "Guardado";
	else
		echo "No Guardado";
	plantHTML(Array(),"action_footer");
	frameGo("fbody","dev.php?command=querybuilder");
	die();
	
} else if ($action=="promote") {
	plantHTML(array(),"action_header");
	$d=newObject("queryb",$ID);
	$vquery="SHOW FULL TABLES WHERE table_type='VIEW'";
		$res=_query($vquery);
		while ($vdata=_fetch_array($res)) {
			$all_views[]=current($vdata);
			
		}
	if (in_array("View_".strtr($d->nombre," ","_"),$all_views))
		$q="ALTER VIEW View_".strtr($d->nombre," ","_")." AS ".$d->queryb;
	else
		$q="CREATE VIEW View_".strtr($d->nombre," ","_")." AS ".$d->queryb;
	$res=_query($q);
	if ($res)
			echo "<div align=\"left\">La consulta $d->nombre ha sido promocionada a vista</div><br>";
	else
			echo "<strong>Error</strong>";
	
	/*
	$d=newObject("queryb",$ID);
	if ($d->delete())
		echo "borrado";
	else
		echo "no borrado";*/
	
	plantHTML(array(),"action_footer");
	frameGo("fbody","dev.php?command=querybuilder");
	die();

}
?>
<?php
				
echo "<script type=\"text/javascript\" language=\"JavaScript\" src=\"{$SYS["ROOT"]}/Extensions/querybuilder_helper.js\"></script>";

?>
		
<table width="95%" border="0" cellspacing="5" align="center" bgcolor="#eeeeee" style="border : 1px #c6c6c6  outset; -moz-border-radius : 10px;">
<TR><TD align="center" bgcolor="#e6a129">
<h2>Core QUERY BUILDER</h2>
</TD></TR>
<TR><TD>

<?php

// 
if ($_POST["QB_CMD"]=="module") {

		$dir=$SYS["BASE"]."/Apps/$module/local/Class/";
		debug("=>>Reading ".$SYS["BASE"]."/Apps/$module/local/Class/","yellow");
		if (is_dir($dir)) {
		if ($dh = opendir($dir)) {
			while (($file = readdir($dh)) !== false) {
					if ((filetype($dir . $file)!="dir")&&(strpos($file,".def")!==False)) {
						$all_classes[]="".str_replace(".def","",$file);
					}
				}
			}
		}
		
	
		echo '
		<br><br><br>
		<div align="center">Selección de Clases Implicadas</div>
		<table width="70%" cellspacing="0" border="0" cellpadding="5" align="center">
		<tbody>
		<tr>
		<td align="center" valign="top"> 
		
				<form action="" method="POST" enctype="multipart/form-data" >
				<select name="clases[]" multiple><br>';
		
		
			foreach ($all_classes as $k=>$v)
						echo "<option value=\"$v\">$v</option>";
		
		
		echo '		</select>
				<br><br>
				<input type="hidden" name="QB_CMD" value="clases"> 
				<input type="hidden" name="modulo" value="'.$module.'"> 
				<input type="submit"> 
				</form>
	
			</td>
		</tr>
		</tbody>
		</table>
		
		

		';
}
else if ($_POST["QB_CMD"]=="clases") {

		set_include_dir($SYS["DOCROOT"]."/../Apps/$modulo/-");
		foreach ($clases as $k=>$v) {
			$o=newObject("$v",1);
				
			foreach(array_keys($o->properties) as $n=>$p) {
				$namee=$o->properties_desc[$p];
				$namex=$o->properties_type[$p];
			
				$data["{$prefix}_$v.$p AS '$namee|$namex'"]="$v.$namee";
				
			}
				
			$data["{$prefix}_$v.ID"]="$v.ID";
			
		}

		echo '
		<br><br><br>
		<div align="center">Seleccione Campos a mostrar</div>
		<table width="70%" cellspacing="0" border="0" cellpadding="5" align="center">
		<tbody>
		<tr>
		<td align="center" valign="top"> 
		
				<form action="" method="POST" enctype="multipart/form-data" >
				<select name="orig[]" multiple><br>';
		
			
			foreach ($data as $k=>$v)
						echo "<option value=\"$k\">$v</option>";
		
		
		echo '		</select>
				<br><br>
				<input type="hidden" name="QB_CMD" value="origs"> 
				<input type="hidden" name="modulo" value="'.$modulo.'"> 
				<input type="hidden" name="clases" value=\''.urlencode(serialize($clases)).'\'> 
				<input type="submit"> 
				</form>
		
			</td>
		</tr>
		</tbody>
		</table>
		
		

		';
}

else if ($_POST["QB_CMD"]=="origs") {

		set_include_dir($SYS["DOCROOT"]."/../Apps/$modulo/-");
		
		
		$clases=unserialize(urldecode($clases));
		$prequery=implode(",",$orig);
		
		
		foreach ($clases as $k=>$v) {
			$o=newObject("$v",1);	
			foreach(array_keys($o->properties) as $n=>$p) {
				
				$namee=$o->properties_desc[$p];
				$data["{$prefix}_$v.$p"]="$v.$namee";
			}
			$data["{$prefix}_$v.ID"]="$v.ID";
			$from_a[]="{$prefix}_$v";				
		}
		$from=implode(",",$from_a);
		
		$query="SELECT $prequery FROM $from WHERE 1=1 ";

		echo $query.'
		<br><br><br>
		<div align="center">Condiciones</div>
				<form action="" method="POST" enctype="multipart/form-data" >
				
		<table width="70%" cellspacing="0" border="0" cellpadding="5" align="center">
		<tr>
		<td align="center" valign="top"> 
		<table border="0"><tr><td colspan="4"> </td></tr>
		
		<tr>
		<td>
		
		
			
				<select name="condition1" ><br>';
		
		
			foreach ($data as $k=>$v)
						echo "<option value=\"$k\">$v</option>";
		
		
		echo '		</select></td><td>
				<select name="condition" ><br>
				<option value="=">=</option>
				<option value="<">&#060;</option>
				<option value=">">&#062;</option>
				<option value="<>"><></option>
				<option value="LIKE">LIKE</option>
				</select>
				</td><td>
				<select name="condition2" >
				<option value="">Entrada manual</option>
				<option value="Funcion">Funcion</option>';
			
			
			foreach ($data as $k=>$v)
						echo "<option value=\"$k\">$v</option>";
		
		echo '
				</select></td><td>
				<input type="text" name="condition3">
				</td></tr></table>
				<input type="hidden" name="QB_CMD" value="condition"> 
				<input type="hidden" name="modulo" value="'.$modulo.'"> 
					<input type="hidden" name="clases" value=\''.urlencode(serialize($clases)).'\'> 
				<input type="hidden" name="queryx"  
					value=\''.urlencode(serialize($query)).'\'> 
				<input type="submit"> 
				
				
				
		
			</td>
		</tr>
		
		</table>
			</td>
		</tr>
		
		</table>
		</form>
		
		

		';
}

else if ($_POST["QB_CMD"]=="condition") {

		set_include_dir($SYS["DOCROOT"]."/../Apps/$modulo/-");
		
		
		$clases=unserialize(urldecode($clases));
		
		foreach ($clases as $k=>$v) {
			$o=newObject("$v",1);	
			foreach(array_keys($o->properties) as $n=>$p) {
				$namee=$o->properties_desc[$p];
				$data["{$prefix}_$v.$p"]="$v.$namee";
			}
			$data["{$prefix}_$v.ID"]="$v.ID";
			$from_a[]="{$prefix}_$v";				
		}
		
		$queryx=unserialize(urldecode($queryx));
		
		if (empty($condition3))
			$query=$queryx." AND $condition1$condition$condition2";
		else {
			if ($condition2!='Funcion')
				$query=$queryx." AND $condition1$condition\'$condition3\'";
			else
				$query=$queryx." AND $condition1$condition$condition3";
		}
		echo $query.'
		<br><br><br>
				<form action="" method="POST" enctype="multipart/form-data" >
				
		<table width="70%" cellspacing="0" border="0" cellpadding="5" align="center">
		
		<tr>
		<td align="center" valign="top"> 
		<table border="0" cellspacing="15"><tr><td colspan="4" align="center"><strong>Condiciones</strong> </td></tr>
		
		<tr>
		<td>
		
		
			
				<select name="condition1" ><br>';
		
		
			foreach ($data as $k=>$v)
						echo "<option value=\"$k\">$v</option>";
		
		
		echo '		</select></td><td>
				<select name="condition" ><br>
				<option value="=">=</option>
				<option value="<">&#060;</option>
				<option value=">">&#062;</option>
				<option value="<>"><></option>
				<option value="LIKE">LIKE</option>
				</select>
				</td><td>
				<select name="condition2" >
				<option value="">Entrada manual</option>
				<option value="Funcion">Funcion</option>';
			
			
			foreach ($data as $k=>$v)
						echo "<option value=\"$k\">$v</option>";
		
		echo '
				</select></td><td>
				<input type="text" name="condition3" id="condition3">
				</td></tr></table>
				<input type="hidden" name="QB_CMD" id ="QB_CMD" value="condition"> 
				
				<input type="hidden" name="modulo" value="'.$modulo.'"> 
					<input type="hidden" name="clases" value=\''.urlencode(serialize($clases)).'\'> 
				<input type="hidden" name="queryx"  
					value=\''.urlencode(serialize($query)).'\'> 
				<input type="submit"> 
				<input type="button" name="finalizar" value="finalizar"
				onclick="document.getElementById(\'QB_CMD\').value=\'end\';this.form.submit()">
				
				
				
		
		
		</td>
		<td>
		<a href="#" onclick="window.open(\''.$SYS["ROOT"].'Framework/Extensions/QueryBuilder/Helper.html\',\'helper\')">Ayuda de funciones</a>
		</td>						
		</tr>
		
		</table>
		</form>
		

		';
}
else if ($_POST["QB_CMD"]=="end") {

		echo $query;
		
		if (empty($_POST["Guardar"])) {
			echo '<br><br>
			<div align="center">
			<form action="" method="POST" enctype="multipart/form-data">
			
			Nombre identificador de la consulta: <input type="text" name="nombre"> 
			
			<input type="hidden" name="queryx"  				value=\''.$queryx.'\'> 
			<input type="hidden" name="QB_CMD"  				value="end"> 
			
			<input type="submit" value="Guardar" name="Guardar"> 
			</form>
			<div align="center"
			';
		
		}
		else {
			$query=unserialize(urldecode($queryx));
			$q=newObject("queryb");
			$q->queryb=$query;
			$q->nombre=$nombre;
			if ($q->save()) {
				echo '<div align="center">Query guardada</div>';
				unset($_POST["QB_CMD"]);
			}
			else
				echo '<div align="center">Error guardando query</div>';
		
		}
		
		
}
if (empty($_POST["QB_CMD"])) {
	$all_classes=array();
	$modules=array("Bilo","Cursos","System");
	foreach ($modules as $k=>$module) { 
		$dir=$SYS["BASE"]."/Apps/$module/local/Class/";
		debug("=>Reading ".$SYS["BASE"]."/Apps/$module/local/Class/","yellow");
		if (is_dir($dir)) {
		if ($dh = opendir($dir)) {
			while (($file = readdir($dh)) !== false) {
					if ((filetype($dir . $file)!="dir")&&(strpos($file,".def")!==False)) {
						$all_classes[]=str_replace(".def","",$file);
					}
				}
			}
		}
	}
	
	$dir=$SYS["DOCROOT"].$SYS["DATADEFPATH"];
	if (is_dir($dir)) {
	if ($dh = opendir($dir)) {
		while (($file = readdir($dh)) !== false) {
				if ((filetype($dir . $file)!="dir")&&(strpos($file,".def")!==False)) {
					$all_classes[]=str_replace(".def","",$file);
				}
			}
		}
	}
	$vquery="SHOW FULL TABLES WHERE table_type='VIEW'";
		$res=_query($vquery);
		while ($vdata=_fetch_array($res)) {
			$all_views[]=current($vdata);
			
		}
/* Module Selection */
	
	
echo '
<br><br><br>
<table width="70%" cellspacing="5" border="0" cellpadding="5" align="center" style="border : 1px #c6c6c6  solid; -moz-border-radius : 4px;">
  <tbody>
    <tr>
    <td colspan="1" align="center" bgcolor="white">
    Nueva consulta
    </td>
    </tr>
    <tr>
      <td align="center"> 
		
		<form action="" method="POST" enctype="multipart/form-data" >
		Selecciona módulo : <select name="module" ><br>';


foreach ($SYS["APPS"] as $k=>$v)
				echo "<option value=\"$v\">$v</option>";


echo '		</select>
		&nbsp;&nbsp;
		<input type="hidden" name="QB_CMD" value="module"> 
		<input type="submit"> 
		</form>
			<hr>O bien, seleccione una vista con la que trabajar:
		<form action="dev.php?command=querybuilder_views" method="POST" enctype="multipart/form-data" >
		<select name="views" >&nbsp;';
		foreach ($all_views as $k=>$v)
						echo "<option value=\"$v\">$v</option>";
		
		
echo '		</select>
				&nbsp;&nbsp;
				<input type="submit"> 
				</form>
	 </td>
    </tr>
  </tbody>
</table>
<br><br>


';


$xx=newObject("queryb");
$xx->searchResults=$xx->selectAll();
setNavVars(array("command"));
listList($xx,array(),"queryb");
}

?>
</TD></TR></table>