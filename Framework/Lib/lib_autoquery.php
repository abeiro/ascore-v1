<?php

function getColNames($views) {
	
	$q=newObject("queryb");
	$vquery="SELECT * FROM $views";
	$bdres=_query($vquery);
	
	$rawres=_fetch_array($bdres);
	if (!$rawres)
		return array();
	$ckeys=array_keys($rawres);
	foreach ($ckeys as $v) {
		$MetaData=explode("|",$v);
		$META[]=$MetaData[0];
	}
	return  $META;
}

function getColNamesFull($views) {
	
	$q=newObject("queryb");
	$vquery="SELECT * FROM $views";
	$bdres=_query($vquery);
	
	$rawres=_fetch_array($bdres);
	if (!$rawres)
		return array();
	$ckeys=array_keys($rawres);
	return  $ckeys;
}

function showQuery($views,$title='') {

	$q=newObject("queryb");
	$vquery="SELECT * FROM $views";
	$bdres=_query($vquery);
	
	for ($i=0,$rows_affected=_affected_rows();$i<$rows_affected;$i++) {
				$rawres=_fetch_array($bdres);
				foreach ($rawres as $kres=>$vres)
					$formated[md5($kres)]=$vres;
				$All[]=$formated;
				$MetaDataAll[]=$rawres;
			}
	
	if (!is_array($All)||(sizeof($All)<1)) {
			die("No hay resultados");
		}
	$ckeys=array_keys($All[0]);		
	$titles=array_keys($MetaDataAll[0]);
		
	if (empty($title))
		$TITLE='<h3 align="center">'.$views.' ('.sizeof($All).')</h3>';
	else
		$TITLE='<h3 align="center">'.$title.' ('.sizeof($All).')</h3>';
	
		$magic_template='
		<!--HEAD-->
		'.$TITLE.'
	<table width="95%" cellspacing="0" border="1" cellpadding="1" align="center" bgcolor="#CECECE" style="border:solid 1px gray">
	<tr>
		
		';
		
	foreach($titles as $row) {
			$row=explode("|",$row);
			
			$magic_template.="
			<th>${row[0]}</th>
			";
			unset($row[0]);
			$metadata[]=implode("|",$row);
		}
	
		$magic_template.='		
	</tr>
	<!--SET-->
	<tr>
	';
		//print_r($keys);
		$j=0;
		$magic=array();
		foreach($MetaDataAll[0] as $row=>$data) {
			$type=explode(":",$metadata[$j]);
			
			if ($type[0]=="date") {
				$cell="<!-- A:{$ckeys[$j]} -->";
			}
			else if ($type[0]=="datex") {
				$cell="<!-- R:{$ckeys[$j]} -->";
			}
			else if ($type[0]=="money") {
				$cell="<!-- S:{$ckeys[$j]} -->";
			}
			else if ($type[0]=="time") {
				$cell="<!-- T:{$ckeys[$j]} -->";
			}
			else if ($type[0]=="ref") {
				$randomkey=md5(time().$ckeys[$j]);
				$magic["$randomkey"]="xxref#{$type[1]}@{$row}@".trim($type[2]);
				$cell="<!-- D:$randomkey -->";
			}
			else
				$cell="<!-- D:{$ckeys[$j]} -->";
				
			$magic_template.="
			<td bgcolor=\"white\">$cell</td>
			";
			$j++;
		}
	$magic_template.='		
	</tr>
	<!--END-->
	</table>';
		
		
		$q->searchResults=$All;
		listList($q,$magic,$magic_template);
}
?>