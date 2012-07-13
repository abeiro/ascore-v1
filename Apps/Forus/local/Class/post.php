<?php

function msgBar($total) {

	$parcial=$this->respuestas+1;
	if ($parcial==1){
	$pc=0;}
	else{
	$pc=$parcial/$total;}
	return gfxBarS($pc,"#FFBA31","white","center","100");

}

function traeOrigen(){

$aux=substr($_SERVER["HTTP_REFERER"],7);
$i=0;
while($aux[$i]!="?" && $i< strlen($aux)){
  $cadena.=$aux[$i];
  $i++;
}
$origen=$cadena;

return $origen;
}

function traeUrl(){

$url=$_SERVER["SERVER_NAME"].$_SERVER["SCRIPT_NAME"];

return $url;
}

?>