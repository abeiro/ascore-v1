<?php
ini_set("max_execution_time","500");
//die("Deactivated");
require_once("System.php");

HTML("action_header");
$ou=newObject("foto");

$data=$ou->selectA();
$total=sizeof($data);
ob_end_flush();
$i=$j=0;
foreach($data as $v) {
	
	
		if ($v["id_foto"]==0) {
			echo $v["ID"]." ".$v["desc"]. " falló<br>";
			$dm=newObject("foto",$v["ID"]);
			$dm->delete();
		}
		else {
			$fh=newObject("fileh",$v["id_foto"]);
			if ((!file_exists($fh->localname()))||(!is_file($fh->localname()))) {
				$dm=newObject("foto",$v["ID"]);
				$dm->delete();
			}
			else {
				$j++;
				//echo $fh->localname();
			}
		}
		
				
		$i++;
		if ($i%25==0) {
			$p=$i*100/$total;
			jsAction("parent.fbody.setProgress('$p');");
			flush();
		}
	
	
}

echo "$i fotos tratadas $j existen";
HTML("action_footer");
?>