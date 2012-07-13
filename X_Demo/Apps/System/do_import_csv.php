<?php

require_once("System.php");

if (!function_exists("array_combine")) {
	/* Ivo van Sandick's array_combine alternative */
	function array_combine( $keys, $vals ) {
		$keys = array_values( (array) $keys );
		$vals = array_values( (array) $vals );
		$n = max( count( $keys ), count( $vals ) );
		$r = array();
		for( $i=0; $i<$n; $i++ ) {
			$r[ $keys[ $i ] ] = $vals[ $i ];
		}
		return $r;
	}
}
 
$SEP_CHAR="#";
$ROW_CHAR="|";

debug("Import elements from CSV","white");
foreach ($SYS["APPS"] as $app)
	set_include_dir($SYS["BASE"]."/Apps/".$app."/.");
	
$data=file($_FILES["fichero_csv"]["tmp_name"]);
$t=fopen($_FILES["fichero_csv"]["tmp_name"],"ro");
$i=0;
while(!feof($t)) {
	$c=fgetc($t);
	
	if ($c==$ROW_CHAR) {
		$data[$i]=$buffer;
		$i++;
		$buffer="";
	}
	else
		$buffer.=$c;
}



$FIELDS=explode($SEP_CHAR,current($data));
foreach ($FIELDS as $a=>$b)
	$FIELDS[$a]=strtolower(trim(strtr($b,"\"'","  ")));

	
 ob_end_flush();

jsAction("setProgress('0');");
foreach($data as $k=>$v) {
	
	if ($k!=0) {
		$DATAFIELDS=explode($SEP_CHAR,$v);
		
		foreach ($DATAFIELDS as $a=>$b)
			$DATAFIELDS[$a]=strtolower(trim(strtr($b,"\"'","  ")));
		
		
		
		$o=newObject("$table");	
		$WaNa=array_combine($FIELDS,$DATAFIELDS);
		
		$o->setAll($WaNa);
		$o->save();		
		if ($k%25==0) {
			$p=$k*100/$i;
			jsAction("setProgress('$p');");
			flush();
		}
	
	}

}
echo "$i lineas tratadas";

?>