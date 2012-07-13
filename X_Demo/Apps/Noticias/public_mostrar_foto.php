<?php
$ID=(isset($ID))?$ID:1;
	
	$pic=newObject("foto",$ID);
	$file=newObject("fileh",$pic->id_foto);
	header("Content-Type: {$file->mime}");
	
	echo $file->getRawData();
	


?>