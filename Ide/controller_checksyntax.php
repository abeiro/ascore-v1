<?php


require(dirname(__FILE__)."/controller_base_js.php");



debug(base64_decode($data),"red");
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime=finfo_file($finfo, dirname(__FILE__)."/../".$file);
finfo_close($finfo);
debug("$mime","red");
$filefullpath=dirname(__FILE__)."/../".$file;

if ($mime=="text/x-php") {
	require(dirname(__FILE__)."/validators/validator_php.php");
	
	$a=ValidateCode($filefullpath);
} else if ($mime=="application/xml") {
	require(dirname(__FILE__)."/validators/validator_xml.php");
	$a=ValidateCode($filefullpath);
} else if ($mime=="text/html") {
	require(dirname(__FILE__)."/validators/validator_html.php");
        $a=ValidateCode($filefullpath);
	
}
else 
	$a=array("msg"=>"Tipo de fichero no reconocido");

$msgbox=new jsO("msgbox","parent",false);

if (is_array($a)) {
		$msgbox->jsO->value="{$a["msg"]} {$a["line"]}";
		$editor=new jsO("editor","parent",true);
		if ($a["line"])
			$editor->setMethod("jumpToLine",array($a["line"]));
		$editor->jsMapO();
		$editor->jsMapM();
	} else {
		 $msgbox->jsO->value="$mime : Syntaxis OK";
	}
	
$msgbox->jsMapO();
$msgbox->jsMapM();
jsCodeFlush();
?>


