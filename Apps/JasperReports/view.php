<?php


require_once("Reports.php");
foreach ($SYS["APPS"] as $ONEAPP)
	set_include_dir(dirname(__FILE__)."/../$ONEAPP/-");
$magic=array();
$ID=(isset($ID))?$ID:1;
$id_curso=(isset($id_curso))?$id_curso:1;
$u=newObject("report",$ID);
$u->id_curso=$id_curso;
if ($u->tipo=='HardCoded') {
	if ($print_mode!="yes") 
		$dummy=1;//plantHTML(array(),"f_menu");
	else {
		if (!ini_get("output_buffering"))
			ob_start();
		
		
	}	
	setLimitRows(5000);
	
	require_once("reports/{$u->url}.php");
	
	
	if ($print_mode!="yes") 
		$dummy=1;//HTML("footer");
	else { 
		$data=ob_get_contents();
		ob_clean();
		require_once("Lib/lib_PDF.php");
		
		if ($SYS["GLOBAL"]["spooler"]=="ASPooler") {
			$cdata=urlencode($data);
			jsAction("location.href='http://".$SYS["GLOBAL"]["ip_spooler"].":9090?$cdata'");
		}
		else
			PDF_html_2_pdf($data);
	}	
}

else 
{

	/* Borramos bufferes */
	if (($print_mode=="yes")||($export_mode=="yes"))
		while(ob_end_clean());
	
	ob_start();
		
		
		
	
	
	$q=newObject("queryb",$u->query_id);
	if (strpos($q->queryb,"SELECT * FROM View")===0) {
		// Es un query de una vista
		require_once("Lib/lib_autoquery.php");
		showQuery(str_replace("SELECT * FROM","",$q->queryb),$u->reportname);
	} else {
		$res=_query(ereg_replace("AS '([a-zA-z\|:0-9 ]*)'","",$q->queryb));
		;
		$bulk=array();
		for ($i=0,$rows_affected=_affected_rows();$i<$rows_affected;$i++) {
				$rawres=_fetch_array($res);
				//$p=array_slice($rawres,1);
				$bulk[]=$rawres;
			}
				
		if (!is_array($bulk)||(sizeof($bulk)<1)) {
			die("No hay resultados");
		}
		$q->searchResults=$bulk;
		$res=_query($q->queryb);
		$rawres=_fetch_array($res);
		$titles=array_keys($rawres);
		
		$magic_template='
		<!--HEAD-->
		<h3 align="center">'.$u->reportname.'('.sizeof($bulk).')</h3>
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
		foreach($bulk[0] as $row=>$data) {
			$type=explode(":",$metadata[$j]);
			
			if ($type[0]=="date") {
				$cell="<!-- A:$row -->";
			}
			else if ($type[0]=="datex") {
				$cell="<!-- R:$row -->";
			}
			else if ($type[0]=="money") {
				$cell="<!-- S:$row -->";
			}
			else if ($type[0]=="time") {
				$cell="<!-- T:$row -->";
			}
			else if ($type[0]=="ref") {
				$randomkey=md5(time().$row);
				$magic["$randomkey"]="xxref#{$type[1]}@{$row}@".trim($type[2]);
				$cell="<!-- D:$randomkey -->";
			}
			else
				$cell="<!-- D:$row -->";
				
			$magic_template.="
			<td bgcolor=\"white\">$cell</td>
			";
			$j++;
		}
	$magic_template.='		
	</tr>
	<!--END-->
	</table>';
		
		//print($magic_template);
		$q->searchResults=$bulk;
		listList($q,$magic,$magic_template);
	}
	
	$data=ob_get_contents();
	ob_clean();
	if ($print_mode=="yes") {
		
		
		require_once("Lib/lib_PDF.php");
		
		PDF_html_2_pdf($data);
		
	}	
	else if ($export_mode=="yes") {
		$trans_from=array(";","</td>","&nbsp","</th>","<tr>");
		$trans_to=array("",";","",";","\n");
		header("Content-Type: text/csv");		
		$origname=strtr($u->reportname," ","_").".csv";				  
		header ("Content-Disposition: attachment; filename=$origname");
		$pass1= strip_tags(str_replace($trans_from,$trans_to,preg_replace("/<h3(.*)h3>/e","",$data )));
		$pass2=strtr($pass1,"\t"," ");
		$pass3=preg_replace("/ \+/"," ",$pass2);
		$pass4=explode("\n",$pass3);
		foreach ($pass4 as $sk=>$cline) {
			$dline=trim($cline);
			if (!empty($dline)) {
				$swords=explode(";",$cline);
					foreach($swords as $sw)
						$wpass[]=trim($sw);
				$fpass[]=implode(";",$wpass);
				unset($wpass);
			}
			
		}
		
		echo implode("\n",$fpass);
	}
	else {
		
		echo $data;
	}
}

if (($print_mode=="yes")||($export_mode=="yes")) {
		while(ob_end_flush());
		die("");
}

?>