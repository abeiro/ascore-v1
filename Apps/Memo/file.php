<?php

$nl=newObject("fileh",$ID);
$realname=$nl->localname();
$origname=$nl->nombre;
if ($databus=fopen($realname,"r")) {
	header("Content-Type: ".$nl->mime);
	header("Content-Length: ".$nl->len);
	header ("Content-Disposition: attachment; filename=$origname");
	
	
	while($data=fread($databus,8192))
		print($data);
	
	fclose($databus);
	$nl->stats=$nl->stats+1;
	$nl->update();
}
else
	echo "File not found";

?>
