<table width="80%" border="0" cellspacing="5" align="center" bgcolor="#eeeeee" style="border : 1px #c6c6c6  outset; -moz-border-radius : 10px;">
<tr>
<td>
<?php

require_once("Lib/lib_autoquery.php");

if ($_POST["submit"]) {
	
	$views=stripslashes(base64_decode($views));
	if (empty($condition3))
			$views.=" AND `$condition1`$condition$condition2";
	else {
			if ($condition2!='Funcion')
				$views.=" AND `$condition1`$condition '$condition3'";
			else
				$views.=" AND `$condition1`$condition$condition3";
	}
	
}
else {
				
	$views.=" WHERE 1=1 ";
}
$cols=getColNames($views);
$rows=getColNamesFull($views);


		
echo '<div align="center">Condiciones</div>
		<form action="" method="POST" enctype="multipart/form-data" >
	<div align="center">		
				<select name="condition1" ><br>';
			foreach ($cols as $k=>$v)
						echo "<option value=\"".$rows[$k]."\">".$cols[$k]."</option>";
		
		
		echo '		</select>
				<select name="condition" ><br>
				<option value="=">=</option>
				<option value="<">&#060;</option>
				<option value=">">&#062;</option>
				<option value="<>"><></option>
				<option value="LIKE">LIKE</option>
				</select>
				
				<select name="condition2" >
				<option value="">Entrada manual</option>
				<option value="Funcion">Funcion</option>';
			
			
		foreach ($cols as $k=>$v)
						echo "<option value=\"".$rows[$k]."\">".$cols[$k]."</option>";
		
		echo '<input type="text" name="condition3" id="condition3">
				<a href="#" onclick="window.open(\''.$SYS["ROOT"].'Framework/Extensions/QueryBuilder/Helper.html\',\'helper\')">Ayuda de funciones</a>	
				<br><br><br><input type="submit" name="submit"> 
			</div>
			<input type="hidden" name="views" value=\''.base64_encode($views).'\'> 		<input type="hidden" name="views" value=\''.base64_encode($views).'\'> 	
		</form>
		';
		
showQuery($views);



?>
		
<div align="center"><form action="dev.php?command=querybuilder" method="POST" enctype="multipart/form-data">
		<input type="hidden" name="action" value="save">
		<input type="hidden" name="queryb" value="<?php echo $views?>">
		Guardar consulta como <input type="text" name="nombre" value="">
		<input type="submit" name="Guardar" value="Guardar">
</form>		
		</div>
</td>
</tr>
</table>